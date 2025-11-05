<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Models\Payroll\Employee;
use App\Http\Controllers\Controller;
use App\Models\Payroll\NoticeBoard;
use Illuminate\Http\Request;

class NoticeBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = NoticeBoard::orderBy('id', 'asc')->get();
        $employees = Employee::all();
        return view('backend.payroll.notice-board.index', compact('notices', 'employees'));
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
    public function store(Request $request)
    {
        $fime_name = null;
        $document = null;
        if ($request->file('file')) {
            $name = $request->file('file')->getClientOriginalName();
            // dd($name);
            $fime_name = pathinfo($name, PATHINFO_FILENAME);
            // dd($fime_name);
            $ext = $request->file('file')->getClientOriginalExtension();
            $document = $fime_name . time() . '.' . $ext;
            $request->file('file')->storeAs('public/upload/notice-board', $document);
        }

        $notice = new NoticeBoard;
        $notice->notice= $request->notice;
        $notice->employee_id= $request->employee_id;
        $notice->fime_name= $fime_name;
        $notice->document= $document;
        // dd($notice);
        $notice->save();

        $notification= array(
            'message'       => 'Notice Create successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
