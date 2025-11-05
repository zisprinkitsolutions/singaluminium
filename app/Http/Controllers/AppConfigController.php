<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\AppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AppConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings= AppConfig::all();
        return view('api-controll-module.app_config.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('api-controll-module.app_config.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'config_name'   => 'required',
            'config_value'   => 'required'
        ]);

        $settings= new AppConfig;
        $settings->config_name      = $request->config_name;
        $settings->config_value     = $request->config_value;
        $settings->save();

        $notification= array(
            'message'       => 'Config Settings Saved!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
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
        $edit_setting= AppConfig::find($id);
        return view('api-controll-module.app_config.form', compact('edit_setting'));
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
        $request->validate([
            'config_name'   => 'required',
            'config_value'   => 'required'
        ]);

        $settings= AppConfig::find($id);
        $settings->config_name      = $request->config_name;
        $settings->config_value     = $request->config_value;
        $settings->save();

        $notification= array(
            'message'       => 'Config Settings Update!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
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
    public function setting_edit_modal(Request $request){
        $edit_setting= AppConfig::find($request->id);
        return view('api-controll-module.app_config.setting-edit-modal', compact('edit_setting'));
    }
}
