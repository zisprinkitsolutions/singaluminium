<?php

namespace App\Models;

use App\AccountType;
use App\JournalRecord;
use App\AccountSubHead;
use App\PurchaseExpenseItem;
use DB;
use App\TempProjectExpense;
use App\JobProjectInvoiceTask;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model
{
    public function masterAccount(){
        return $this->belongsTo(MasterAccount::class, 'master_account_id');
    }
    public function acAmount(){
        return $this->hasMany(JournalRecord::class, 'account_head_id');
    }

    public function accType(){
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function acAmountCalculate(){
        return $this->hasMany(JournalRecord::class, 'account_head_id')->where('transaction_type', 'DR');
    }
    
    public function sub_heads()
    {
        return $this->hasMany(AccountSubHead::class,'account_head_id');
    }
    public function purchase_item(){
        return $this->hasMany(PurchaseExpenseItem::class, 'head_id')->where('type', 'Raw Material');
    }
    
    public function stock(){
        return $this->hasOne(StockTransection::class, 'product_id');
    }
    
    public function openning_product_office( $date, $productId)
    {
        $last_oppening = StockTransection::where('sub_head_id', null)->where('product_id', $productId)
            ->where('transection_code', 'o')
            ->whereDate('date', '<', $date)
            ->orderBy('id', 'DESC')->first();
        if ($last_oppening) {
            $qty_in = StockTransection::where('sub_head_id', null)->where('id', '>=', $last_oppening->id)
                ->where('stock_effect', '1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

                $qty_out = StockTransection::where('sub_head_id', null)->where('id', '>=', $last_oppening->id)
                ->where('stock_effect', '-1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

             $openning = $qty_in-$qty_out;

            $total_price = StockTransection::where('sub_head_id', null)->where('id', '>=', $last_oppening->id)
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
            $qty_in = StockTransection::where('sub_head_id', null)->where('stock_effect', '1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

                $qty_out = StockTransection::where('sub_head_id', null)->where('stock_effect', '-1')
                ->where('product_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

            $openning = $qty_in-$qty_out;

            $total_price = StockTransection::where('sub_head_id', null)->where('stock_effect', '1')
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
        $qty_in = StockTransection::where('sub_head_id', null)->where('stock_effect', '1')
            ->where('product_id', $productId)
            ->when($date && $to ==null, function ($query) use ($date) {
                return $query->whereDate('date', $date);
            })
            ->when($date && $to, function ($query) use ($date, $to) {
                return $query->whereDate('date', '>=', $date)->whereDate('date', '<=', $to);
            })
            ->sum('quantity');
        $total_price = StockTransection::where('sub_head_id', null)->where('stock_effect', '1')
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
        $qty_in = StockTransection::where('sub_head_id',null)->where('stock_effect', '1')->where('transection_code', 'SR')
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
        $qty_out = StockTransection::where('sub_head_id',null)->where('stock_effect', '-1')->whereIn('transection_type', ['sales', 'Reconciliation'])
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
    public function committed_stock(){
        return $this->hasMany(TempProjectExpense::class, 'account_head_id');
    }
}
