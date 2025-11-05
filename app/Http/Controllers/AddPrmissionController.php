<?php

namespace App\Http\Controllers;

use App\AddPrmission;
use App\Module;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddPrmissionController extends Controller
{

    public function edit(Request $request)
    {
        Gate::authorize('user');
        $user_id = $request->id; // Get the user id from the request

        $modules = Module::all();

        $user = User::find($user_id);
       // return($user->addPermissions);
        $role = $user->role_id;
        return view('backend.user.permission',compact('modules','role','user'));
    }


    public function update(Request $request)
    {
        Gate::authorize('user');
        // $request->validate([

        //     'permissions'   => 'required|array',
        //     'permissions.*'   => 'required|integer',
        // ]);
        $user= User::find($request->user_id);

        $user->addPermissions()->sync($request->input('permissions',[]));
        $notification = array(
            'message'=>'Aditional Permission Update Successfull',
            'alert-type'=>'success'
        );
        return back()->with($notification);
        // return redirect()->route('role.index');
    }

}
