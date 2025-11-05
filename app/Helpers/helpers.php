<?php
// File: app/helpers.php
use App\JobProjectInvoice;
use App\Sale;
use App\Models\InvoiceNumber;
use App\Journal;
use Carbon\Carbon;
use App\BankInvoice;
use App\Models\Payroll\EmployeePolicy;
use App\Models\Payroll\WeekendHoliday;

if (!function_exists('generateUniqueNumber')) {
    function generateUniqueNumber($modelName, $prefix, $columnName)
    {
        if (strpos($modelName, 'App\\') === 0) {
            $model = $modelName;
        } else {
            $model = "App\\" . $modelName; // Assuming all models are within the App namespace
        }

        $latestInvoice = $model::latest()->first();
        if ($latestInvoice) {
            $invoiceNumber = preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $latestInvoice->$columnName);
            $invoiceNumber++;
        } else {
            $invoiceNumber = 1;
        }

        if ($invoiceNumber < 10) {
            $invoiceNumber = $prefix . "000" . $invoiceNumber;
        } elseif ($invoiceNumber < 100) {
            $invoiceNumber = $prefix . "00" . $invoiceNumber;
        } elseif ($invoiceNumber < 1000) {
            $invoiceNumber = $prefix . "0" . $invoiceNumber;
        } else {
            $invoiceNumber = $prefix . $invoiceNumber;
        }

        return $invoiceNumber;
    }

}

if (!function_exists('policy_helper')) {
    function policy_helper($emp_id, $date = null)
     {

         $date  = $date ?? date('Y-m-d');

        $policy =  EmployeePolicy::where('employee_id', $emp_id)
         ->where('effect_date', '>=',$date)
         ->orderBy('id', 'desc')
         ->first() ?? EmployeePolicy::where('employee_id', $emp_id)
         ->where('effect_date', '<=', $date)
         ->orderBy('id', 'desc')
         ->first() ;
        if($policy){
            return $policy;
        }

        return false;
     }
 }
 if (!function_exists('check_holiday_helper')) {
     function check_holiday_helper($emp_id , $date){
         $holiday = WeekendHoliday::where('emp_id',$emp_id)->where('date', $date)->where('status', 1)->first();
         return  $holiday ? false : true ;
     }
  }

if(!function_exists('secondsTotime')){
    function secondsToTime($seconds) {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }
}

if(!function_exists('change_date_format')){
    function change_date_format(string $date):string{
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
}

