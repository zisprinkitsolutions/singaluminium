<?php

namespace App;
use App\Models\StockTransection;
use DB;
use Illuminate\Database\Eloquent\Model;

class AccountSubHead extends Model
{
    public function sub_stock(){
        return $this->hasMany(StockTransection::class, 'sub_head_id');
    }

    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function sub_head_openning_product_office( $date, $productId)
    {
        $last_oppening = StockTransection::where('sub_head_id', $productId)
            ->where('transection_code', 'o')
            ->whereDate('date', '<', $date)
            ->orderBy('id', 'DESC')->first();
        if ($last_oppening) {
            $qty_in = StockTransection::where('id', '>=', $last_oppening->id)
                ->where('stock_effect', '1')
                ->where('sub_head_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

                $qty_out = StockTransection::where('id', '>=', $last_oppening->id)
                ->where('stock_effect', '-1')
                ->where('sub_head_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

             $openning = $qty_in-$qty_out;

            $total_price = StockTransection::where('id', '>=', $last_oppening->id)
                ->where('stock_effect', '1')
                ->where('sub_head_id', $productId)
                ->where('date', '<', $date)
                ->sum(DB::raw('remaining_stock * unit_price'));
            if ($qty_in != 0) {
                $weight_avarage = $total_price / $qty_in;
            } else {
                $weight_avarage = 0;
            }
        } else {
            $qty_in = StockTransection::where('stock_effect', '1')
                ->where('sub_head_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

                $qty_out = StockTransection::where('stock_effect', '-1')
                ->where('sub_head_id', $productId)
                ->where('date', '<', $date)
                ->sum('quantity');

            $openning = $qty_in-$qty_out;

            $total_price = StockTransection::where('stock_effect', '1')
                ->where('sub_head_id', $productId)
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
    public function sub_head_quantity_in( $date, $to, $productId)
    {
        $qty_in = StockTransection::where('stock_effect', '1')
            ->where('sub_head_id', $productId)
            ->when($date && $to ==null, function ($query) use ($date) {
                return $query->whereDate('date', $date);
            })
            ->when($date && $to, function ($query) use ($date, $to) {
                return $query->whereDate('date', '>=', $date)->whereDate('date', '<=', $to);
            })
            ->sum('quantity');
        $total_price = StockTransection::where('stock_effect', '1')
            ->where('sub_head_id', $productId)
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
    public function sub_head_quantity_out( $date, $to, $productId)
    {
        $qty_out = StockTransection::where('stock_effect', '-1')->whereIn('transection_type', ['sales', 'Reconciliation'])
            ->where('sub_head_id', $productId)
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
}
