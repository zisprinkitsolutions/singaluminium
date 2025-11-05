<?php

namespace App\Providers;
use App\User;
use App\Asset;
use App\AssetDepreciation;
use App\Currency;
use App\DebitCreditVoucher;
use App\Journal;
use App\CostCenterType;
use App\VatRate;
use App\JournalRecord;
use App\Models\AccountHead;
use App\Models\MstACType;
use App\Models\MstDefinition;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeAttendance;
use App\Models\Payroll\EmployeePolicy;
use App\Models\Payroll\WeekendHoliday;
use App\Setting;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        if (env('APP_ENV') === 'local' && str_ends_with($request->getHost(), '.ngrok-free.app')) {
            URL::forceScheme('https');
        }
        $partyTypes=CostCenterType::get();
        view()->share('partyTypes', $partyTypes);
        Blade::if('role', function ($role) {
            return Auth::user()->role->slug == $role;
        });

        $running_no = Setting::where('config_name', 'running_no')->first()->config_value;
        view()->share('running_no', $running_no);

        $trn_no = Setting::where('config_name', 'trn_no')->first()->config_value;
        view()->share('trn_no', $trn_no);
        $company_name = Setting::where('config_name', 'company_name')->first()->config_value;
        view()->share('company_name', $company_name);

        view()->composer('*', function ($view) {
            $currency = Currency::find(1);
            $view->with('currency', $currency);
        });

        $standard_vat_rate=VatRate::find(1)->value;
        view()->share('standard_vat_rate', $standard_vat_rate);

        $units=Unit::get();
        view()->share('units', $units);

        // *******************AUTO ATTENDANCE ABSEN ADD FUNCTIONALITY*************************

        $today = date('Y-m-d');
        $previousMonthDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $check = DB::table('daily_check')->first();

        // If no daily check or the date is outdated, proceed with the attendance check
        if (!$check || $check->date != $today) {
            $employees = Employee::all();
            $datesToCheck = Carbon::parse($previousMonthDate)->daysUntil(Carbon::yesterday());

            $newRecords = [];

            foreach ($datesToCheck as $date) {
                foreach ($employees as $emp) {
                    $emp_policy = policy_helper($emp->emp_id,$date);

                    $weekendHoliday = $emp->weekend($emp->emp_id,$date);

                    // Check if an attendance record already exists for this employee and date
                    $existingAttendance = EmployeeAttendance::where('date', '=', $date->format('Y-m-d'))
                        ->where('employee_id', $emp->id)
                        ->exists();

                    if (!$existingAttendance) {
                        $status = $weekendHoliday['weekend'] != '' ? 3 : 0;

                        $newRecords[] = [
                            'employee_id' => $emp->id,
                            'status' => $status,
                            'date' => $date->format('Y-m-d'),
                            'in_time' => '00:00:00',
                            'out_time' => '00:00:00',
                            'evening_in' => '00:00:00',
                            'evening_out' => '00:00:00',
                            'reference_in_time' => $emp_policy->m_ref_in_time ?? '00:00:00',
                            'reference_out_time' => $emp_policy->m_ref_out_time ?? '00:00:00',
                            'e_reference_in_time' => $emp_policy->e_ref_in_time ?? '00:00:00',
                            'e_reference_out_time' => $emp_policy->e_ref_out_time ?? '00:00:00',
                            'project_id' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            if (!empty($newRecords)) {
                foreach (array_chunk($newRecords, 1000) as $chunk) {
                    EmployeeAttendance::insert($chunk);
                }
            }

            // Update or create the daily check record
            DB::table('daily_check')->updateOrInsert(
                ['id' => $check->id ?? null],
                ['date' => $today]
            );
        }
    }
}
