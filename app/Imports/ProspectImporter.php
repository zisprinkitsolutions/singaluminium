<?php

namespace App\Imports;

use App\NewProject;
use App\PartyInfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DateTime;

class ProspectImporter implements ToModel, WithHeadingRow
{
    protected $skippedRows = [];

    public function model(array $row)
    {
        // Map using header names from Excel
        $projectName    = trim($row['project_name'] ?? '');
        $ownerName      = trim($row['owner_name'] ?? '');
        $plot           = trim($row['plot'] ?? '');
        $location       = trim($row['location'] ?? '');
        $projectNo      = trim($row['project_no'] ?? '');
        $projectType    = trim($row['project_type'] ?? '');
        $engineer       = trim($row['engineer'] ?? '');
        $shortName      = trim($row['short_name'] ?? '');
        $consultant     = trim($row['consultant'] ?? '');
        $contractValue  = $this->parseDecimal($row['contract_value'] ?? null);
        $vat            = $this->parseDecimal($row['vat'] ?? null);
        $variation      = $this->parseDecimal($row['variation'] ?? null);
        $totalContract  = $this->parseDecimal($row['total_contract'] ?? null);
        $estimation     = trim($row['estimation'] ?? '');
        $psBudget       = trim($row['ps_budget'] ?? '');
        $status         = trim($row['status'] ?? '');
        $insurance      = trim($row['insurance'] ?? '');
        $contract       = trim($row['contract'] ?? '');
        $contractPeriod = trim($row['contract_period'] ?? '');
        $area           = trim($row['area'] ?? '');
        $fileNo         = trim($row['file_no'] ?? '');
        $startDate      = $this->parseDate($row['start_date'] ?? null);
        $deadline       = $this->parseDate($row['deadline'] ?? null);
        $date           = $this->parseDate($row['date'] ?? null);
        $mobileNo       = trim($row['mobile_no'] ?? '');
        $details        = trim($row['details'] ?? '');
        $handoverOn     = $this->parseDate($row['handover_on'] ?? null);

        // Validation: skip if required fields missing
        if (empty($projectName) || empty($projectNo) || empty($projectType) ) {
            $message = "Skipping Project: " . ($projectName ?: '[No Name]') . " - Required fields missing";
            if (!in_array($message, $this->skippedRows)) {
                $this->skippedRows[] = $message;
            }
            return null;
        }

        // Find or create party based on ownerName
        $party = null;
        if (!empty($ownerName)) {
            $latest = PartyInfo::withTrashed()->orderBy('id','DESC')->first();

            if ($latest) {
                $pi_code = preg_replace('/^PI-/', '', $latest->pi_code);
                ++$pi_code;
            } else {
                $pi_code = 1;
            }

            if ($pi_code < 10) {
                $c_code = "PI-000" . $pi_code;
            } elseif ($pi_code < 100) {
                $c_code = "PI-00" . $pi_code;
            } elseif ($pi_code < 1000) {
                $c_code = "PI-0" . $pi_code;
            } else {
                $c_code = "PI-" . $pi_code;
            }
            // $titlesToRemove = ['mr.', 'mrs.', 'ms.', 'dr.', 'miss', 'prof.', 'sir', 'madam'];
            // $cleanOwnerName = trim(preg_replace('/^(' . implode('|', array_map('preg_quote', $titlesToRemove)) . ')\s+/i', '', $ownerName));
            // $party = PartyInfo::where('pi_name', $cleanOwnerName)->first();

            $party = PartyInfo::where('pi_name', $ownerName)->first();

            if (!$party) {
                $party = new PartyInfo();
                // $party->pi_name = $cleanOwnerName;
                $party->pi_name = $ownerName;
                $party->pi_type = 'Customer';
                $party->pi_code = $c_code;
                $party->save();
            }
        }


        return new NewProject([
            'name'           => $projectName,
            'project_no'     => $projectNo,
            'project_type'   => $projectType,
            'project_code'   => $projectNo,             // you can assign if needed
            'party_id'       => $party ? $party->id : null,
            'mobile_no'      => $mobileNo,
            'location'       => $location,
            'consultant'     => $consultant,
            'start_date'     => $startDate,
            'end_date'       => null,
            'details'        => $details,
            'handover_on'    => $handoverOn,
            'project_status' => $status ?: 'Planned',
            'total_amount'   => null,
            'plot'           => $plot,
            'engineer'       => $engineer,
            'short_name'     => $shortName,
            'contract_value' => $contractValue,
            'vat'            => $vat,
            'variation'      => $variation,
            'total_contract' => $totalContract,
            'estimation'     => $estimation,
            'ps_budget'      => $psBudget,
            'status'         => $status,
            'date'           => $date,
            'insurance'      => $insurance,
            'contract'       => $contract,
            'contract_period'=> $contractPeriod,
            'area'           => $area,
            'file_no'        => $fileNo,
            'deadline'       => $deadline,
        ]);
    }

    protected function parseDate($rawDate): ?string
    {
        if (empty($rawDate)) {
            return null;
        }
        if (is_numeric($rawDate)) {
            return gmdate("Y-m-d", ($rawDate - 25569) * 86400);
        }
        $formats = ['d-m-y', 'd/m/y', 'Y-m-d', 'd.m.Y', 'm/d/Y'];
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $rawDate);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }
        return null;
    }
    protected function parseDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        return is_numeric($value) ? (float) $value : null;
    }

    public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }
}
