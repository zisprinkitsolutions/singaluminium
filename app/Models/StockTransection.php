<?php

namespace App\Models;
use App\TempProjectExpense;
use App\JobProjectInvoiceTask;
use App\PurchaseExpenseItem;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockTransection extends Model
{
    protected $fillable = [
        'product_id',
        'office_id',
        'transection_id',
        'transection_type',
        'stock_effect',
        'transection_code',
        'quantity',
        'unit_price',
        'remaining_stock',
        'consumed_quantity',
    ];
    public function product()
    {
        return $this->belongsTo(AccountHead::class, 'product_id');
    }
    public function profit_loss($productId)
    {
        $totalProfitLoss = JobProjectInvoiceTask::where('item_description', $productId)
            ->selectRaw('(SUM(rate * qty) - SUM(cost_price * qty)) AS profit_loss')
            ->value('profit_loss');
        return $totalProfitLoss;
    }
    public function openning_product_office( $date, $productId)
    {
        $last_oppening = StockTransection::where('product_id', $productId)
            ->where('transection_code', 'o')
            ->whereDate('date', '<', $date)
            ->orderBy('id', 'DESC')->first();
        if ($last_oppening) {
            $qty_in = StockTransection::where('id', '>=', $last_oppening->id)
                ->where('stock_effect', '1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

                $qty_out = StockTransection::where('id', '>=', $last_oppening->id)
                ->where('stock_effect', '-1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

             $openning = $qty_in-$qty_out;

            $total_price = StockTransection::where('id', '>=', $last_oppening->id)
                ->where('stock_effect', '1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum(DB::raw('remaining_stock * unit_price'));
            if ($qty_in != 0) {
                $weight_avarage = $total_price / $qty_in;
            } else {
                $weight_avarage = 0;
            }
        } else {
            $qty_in = StockTransection::where('stock_effect', '1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

                $qty_out = StockTransection::where('stock_effect', '-1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

            $openning = $qty_in-$qty_out;

            $total_price = StockTransection::where('stock_effect', '1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum(DB::raw('remaining_stock * unit_price'));
            if ($qty_in != 0) {
                $weight_avarage = $total_price / $qty_in;
            } else {
                $weight_avarage = 0;
            }
        }

        return [
            'opening' => number_format($openning,2,'.',''),
            'weight_avarage' => $weight_avarage,
        ];
    }
    public function quantity_in( $date, $to, $productId)
    {
        $qty_in = StockTransection::where('stock_effect', '1')
            ->where('product_id', $productId)
            ->when($date && $to ==null, function ($query) use ($date) {
                return $query->whereDate('date', $date);
            })
            ->when($date && $to, function ($query) use ($date, $to) {
                return $query->whereDate('date', '>=', $date)->whereDate('date', '<=', $to);
            })
            ->sum('quantity');
        $total_price = StockTransection::where('stock_effect', '1')
            ->where('product_id', $productId)
            ->when($date && $to ==null, function ($query) use ($date) {
                return $query->whereDate('date', $date);
            })
            ->when($date && $to, function ($query) use ($date, $to) {
                return $query->whereDate('date', '>=', $date)->whereDate('date', '<=', $to);
            })
            ->sum(DB::raw('remaining_stock * unit_price'));

        if ($qty_in != 0) {
            $weight_avarage = $total_price / $qty_in;
        } else {
            $weight_avarage = 0;
        }
        return [
            'quantity_in' => number_format($qty_in,2,'.',''),
            'weight_avarage'=>$weight_avarage,

        ];
    }
    public function return_product( $date, $to, $productId)
    {
        $qty_in = StockTransection::where('stock_effect', '1')->where('transection_code', 'SR')
            ->where('product_id', $productId)
            ->when($date && $to ==null, function ($query) use ($date) {
                return $query->whereDate('date', $date);
            })
            ->when($date && $to, function ($query) use ($date, $to) {
                return $query->whereDate('date', '>=', $date)->whereDate('date', '<=', $to);
            })
            ->sum('quantity');

        return [
            'quantity_return' => number_format($qty_in,2,'.',''),
        ];
    }
    public function quantity_out( $date, $to, $productId)
    {
        $qty_out = StockTransection::where('stock_effect', '-1')->whereIn('transection_type', ['sales', 'Reconciliation'])
            ->where('product_id', $productId)
            ->when($date && $to ==null, function ($query) use ($date) {
                return $query->whereDate('date', $date);
            })
            ->when($date && $to, function ($query) use ($date, $to) {
                return $query->whereDate('date', '>=', $date)->when($to, function ($query) use ($to) {
                    return $query->whereDate('date', '<=', $to);
                });
            })
            ->sum('quantity');
        return number_format($qty_out,2,'.','');
    }
    public function purchase_return( $date, $to, $productId)
    {
        $qty_out = StockTransection::where('stock_effect', '-1')->where('transection_type', 'Purchse Retrun')
            ->where('product_id', $productId)
            ->when($date && $to ==null, function ($query) use ($date) {
                return $query->whereDate('date', $date);
            })
            ->when($date && $to, function ($query) use ($date, $to) {
                return $query->whereDate('date', '>=', $date)->when($to, function ($query) use ($to) {
                    return $query->whereDate('date', '<=', $to);
                });
            })
            ->sum('quantity');
        return number_format($qty_out,2,'.','');
    }
    public function last_purchase_price(){
        return $this->hasOne(PurchaseExpenseItem::class, 'head_id')->orderBy('id', 'desc');
    }
    public function committed_stock(){
        return $this->hasMany(TempProjectExpense::class, 'account_head_id');
    }
}
