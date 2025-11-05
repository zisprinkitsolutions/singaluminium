<?php

namespace App\Imports;

use App\BillOfQuantity;
use App\BillOfQuantityItem;
use App\BillOfQuantityTask;
use App\BoqItemDetail;
use App\BoqTaskName;
use App\NewProject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use DateTime;
class BOQExcelImport implements ToModel, WithStartRow
{
    protected $boqs = [];
    protected $tasks = [];
    protected $skippedRows = [];

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        if (empty(array_filter($row)) || strtolower(trim($row[0] ?? '')) === 'project name') {
            return null;
        }

        $projectName = trim($row[0] ?? '');
        $projectNO   = trim($row[1] ?? '');
        $rawDate     = $row[2] ?? '';
        $taskName    = trim($row[3] ?? '');
        $itemDesc    = trim($row[4] ?? '');
        $unit        = trim($row[5] ?? '');
        $qty         = is_numeric($row[6] ?? null) ? floatval($row[6]) : 0;
        $rate        = is_numeric($row[7] ?? null) ? floatval($row[7]) : 0;

        // Get or create BOQ for this project
        if (!isset($this->boqs[$projectNO])) {
            [$boq, $isNew] = $this->createOrGetBoq($projectNO, $rawDate);
            $this->boqs[$projectNO] = ['boq' => $boq, 'is_new' => $isNew];
        }

        $boqData = $this->boqs[$projectNO];
        $boq     = $boqData['boq'];
        $isNew   = $boqData['is_new'];

        if (!$boq) {
            $message = "Skipping Project : $projectName";
            if (!in_array($message, $this->skippedRows)) {
                $this->skippedRows[] = $message;
            }
            return null;
        }

        // If BOQ already existed, do NOT create tasks or items
        if (!$isNew) {
            return null;
        }

        // Get or create task for this new BOQ
        if (!isset($this->tasks[$boq->id][$taskName])) {
            $this->tasks[$boq->id][$taskName] = $this->findOrCreateTask($boq, $taskName);
        }

        $task = $this->tasks[$boq->id][$taskName];

        // Create item if description exists
        if ($itemDesc) {
            return $this->createItem($task, $itemDesc, $unit, $qty, $rate);
        }

        return null;
    }

    protected function createOrGetBoq(string $projectNO, $rawDate): array
    {
        $project = NewProject::where('project_no', $projectNO)->first();

        if (!$project) {
            return [null, false];
        }

        // Check if BOQ already exists
        $existingBoq = BillOfQuantity::where('project_id', $project->id)->first();
        if ($existingBoq) {
            return [$existingBoq, false]; // false = not new
        }

        // Create new BOQ
        $date = $this->parseDate($rawDate);

        $lastBoq     = BillOfQuantity::latest('id')->first();
        $lastNumber  = $lastBoq ? intval(preg_replace('/[^0-9]/', '', $lastBoq->boq_no)) : 0;
        $nextNumber  = $lastNumber + 1;
        $boqNo       = 'BOQ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $newBoq = BillOfQuantity::create([
            'boq_no'     => $boqNo,
            'project_id' => $project->id,
            'name'       => $project->name,
            'party_id'   => $project->party_id,
            'compnay_id' => $project->compnay_id ?? null,
            'date'       => $date,
            'status'     => 1
        ]);

        return [$newBoq, true]; // true = newly created
    }


    protected function findOrCreateTask(BillOfQuantity $boq, string $taskName): BillOfQuantityTask
    {
        $existing = BillOfQuantityTask::where('boq_id', $boq->id)
            ->where('name', $taskName)
            ->first();

        if ($existing) return $existing;

        return BillOfQuantityTask::create([
            'boq_id' => $boq->id,
            'name' => $taskName,
            'progress' => 0,
        ]);
    }

    protected function createItem(BillOfQuantityTask $task, string $description, string $unit, float $qty, float $rate): BillOfQuantityItem
    {
        $item = BillOfQuantityItem::create([
            'task_id' => $task->id,
            'item_description' => $description,
            'unit' => $unit,
            'qty' => $qty,
            'rate' => $rate,
            'sub_task_id' => 0,
        ]);

        $this->updateTaskContactAmount($task);

        return $item;
    }

    protected function updateTaskContactAmount(BillOfQuantityTask $task): void
    {
        $total = BillOfQuantityItem::where('task_id', $task->id)->sum('total');
        $task->contact_amount = $total;
        $task->save();
    }

    protected function parseDate($rawDate): string
    {
        if (is_numeric($rawDate)) {
            return gmdate("Y-m-d", ($rawDate - 25569) * 86400); // Excel serial to date
        }

        $date = DateTime::createFromFormat('d-m-y', $rawDate)
            ?: DateTime::createFromFormat('d/m/y', $rawDate)
            ?: DateTime::createFromFormat('Y-m-d', $rawDate)
             ?: DateTime::createFromFormat('d.m.Y', $rawDate)
            ?: now();

        return $date->format('Y-m-d');
    }
      public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }
}
