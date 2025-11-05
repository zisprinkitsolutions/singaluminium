<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobProjectPaymentStoreRequest;
use App\JobProject;
use App\JobProjectPayment;
use Illuminate\Http\Request;

class JobProjectPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = JobProject::latest()->where('is_invoice',1)->get();

        $project_payments = JobProject::whereHas('payments')
        ->with(['payments'=>function($q){
            $q->orderBy('date');
        }])->latest()->paginate(20);


        $payments = $project_payments->map(function($project){
            return[
                'id' => $project->id,
                'party_name' => $project->party->pi_name,
                'project_name' => $project->project_name,
                'total_budget' => $project->tasks->sum('budget'),
                'payment_amount' => $project->payments->sum('payment_amount'),
                'due_amount' => $project->tasks->sum('budget') - $project->payments->sum('payment_amount'),
                'date' => $project->payments[0]->date,
            ];
        });

        return view('backend.job-project-payment.index',compact('projects','payments','project_payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobProjectPaymentStoreRequest $request)
    {
        $data = $request->only('job_project_id','party_info_id','payment_amount');

        $date_array = explode('/',$request->date);
        $date_string = implode('-',$date_array);
        $date_time = date('Y-m-d',strtotime($date_string));
        $date = \DateTime::createFromFormat('Y-m-d',$date_time);

        $data['date'] = $date;

        $payment =  JobProjectPayment::create($data);


        return [
            'project_name' => $payment->project->project_name,
            'party_name' => $payment->party->pi_name,
            'budget' => $payment->project->tasks->sum('budget'),
            'payment_amount' => $payment->payment_amount,
            'due' => $payment->project->tasks->sum('budget') - $payment->payment_amount,
            'date' => $payment->date->format('d/m/Y'),
            'id' => $payment->id,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JobProjectPayment  $jobProjectPayment
     * @return \Illuminate\Http\Response
     */
    public function show(JobProject $payment) //$payment mean $jobproject
    {
        return view('backend.job-project-payment.view',compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobProjectPayment  $jobProjectPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(JobProjectPayment $jobProjectPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobProjectPayment  $jobProjectPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobProjectPayment $jobProjectPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobProjectPayment  $jobProjectPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobProjectPayment $jobProjectPayment)
    {
        //
    }
}
