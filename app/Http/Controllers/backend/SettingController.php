<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('settings');
        $settings= Setting::all();
        return view('backend.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.settings.form');
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

        $settings= new Setting;
        $settings->config_name      = $request->config_name;
        $settings->config_value     = $request->config_value;
        $settings->save();

        $notification= array(
            'message'       => 'Settings Saved!',
            'alert-type'    => 'success'
        );
        return redirect('administration/settings')->with($notification);
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
        $edit_setting= Setting::find($id);
        return view('backend.settings.form', compact('edit_setting'));
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

        $settings= Setting::find($id);
        $settings->config_name      = $request->config_name;
        $settings->config_value     = $request->config_value;
        $settings->save();

        $notification= array(
            'message'       => 'Settings Update!',
            'alert-type'    => 'success'
        );
        return redirect('administration/settings')->with($notification);
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
        $edit_setting= Setting::find($request->id);
        return view('backend.settings.setting-edit-modal', compact('edit_setting'));
    }
}
