<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Module;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('manage_profile');

        $modules = Module::all();
        $RoleQuery = Role::query();

        if ($request->search) {
            $RoleQuery->Where('name', 'like', '%' . $request->search . '%');
        }
        $roles = $RoleQuery->get();

        return view('backend.role.index', compact('roles', 'modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('manage_profile');
        $modules = Module::all();
        return view('backend.role.form', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('manage_profile');
        // return $request;

        $request->validate([
            'role_name'     => 'required',
            'role_name' => 'required|unique:roles,name',

            'permissions'   => 'required|array',
            'permissions.*'   => 'required|integer',
        ]);

        Role::create([
            'name'  => $request->role_name,
            'slug'  => Str::slug($request->role_name)
        ])->permissions()->sync($request->input('permissions', []));

        $notification = array(
            'message' => 'Role Add Successfull',
            'alert-type' => 'success'
        );
        return back()->with($notification);
        // return redirect()->route('role.index');
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
        Gate::authorize('manage_profile');
        $role = Role::find($id);
        $modules = Module::all();

        return view('backend.role.form', compact('modules', 'role'));
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
        Gate::authorize('manage_profile');
        $request->validate([
            'role_name' => 'required|unique:roles,name,' . $id,
            'permissions'   => 'required|array',
            'permissions.*'   => 'required|integer',
        ]);

        $role = Role::find($id);
        $role->update([
            'name'  => $request->role_name,

            'slug'  => Str::slug($request->role_name)
        ]);

        $role->permissions()->sync($request->input('permissions', []));
        $notification = array(
            'message' => 'Role Update Successfull',
            'alert-type' => 'success'
        );
        return redirect('administration/role')->with($notification);
        // return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('manage_profile');
        //
    }
    public function role_edit_modal(Request $request)
    {
        Gate::authorize('manage_profile');
        $role = Role::find($request->id);
        $modules = Module::all();
        return view('backend.role.role-edit-modal', compact('role', 'modules'));
    }
}
