<?php

namespace App\Http\Controllers\backend;

use App\CountryHeadOffice;
use App\Http\Controllers\Controller;
use App\OfficeType;
use App\ProjectDetail;
use App\ProjectDetailsType;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('user');
        $rolesQuery = Role::query();
        $usersQuery = User::query();

        $roles = $rolesQuery->get();

        if (auth()->user()->email !== 'login@zisprink.com') {
            $usersQuery->where('email', '!=', 'login@zisprink.com');
        }

        $usersQuery->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        });

        $users = $usersQuery->get();



        return view('backend.user.index', compact('users', 'roles'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('user');
        $roles = Role::all();
        return view('backend.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('user');
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required'],
        ]);
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role_id' => $request['role_id'],


        ]);
        $notification = array(
            'message'       => 'User Create successful!',
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
        Gate::authorize('user');
        $user = User::find($id);
        $roles = Role::all();
        $branch = ProjectDetail::get();
        return view('backend.user.edit', compact('user', 'roles', 'branch'));
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
        $user = User::findOrFail($id);

        $user->name  = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->role_id            = $request->input('role_id');
        $user->is_creator         = $request->boolean('is_creator', false);
        $user->is_authorizer      = $request->boolean('is_authorizer', false);
        $user->is_approver        = $request->boolean('is_approver', false);
        $user->max_approve_amount = $request->input('max_approve_amount') ?? null;

        $user->save();

        return redirect()->back()->with([
            'message'    => 'User updated successfully!',
            'alert-type' => 'success',
        ]);
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
    public function user_edit_modal(Request $request)
    {
        Gate::authorize('user');
        $roles = Role::get();
        $user = User::find($request->id);

        return view('backend.user.user-edit-modal', compact('user', 'roles'));
    }
    public function find_outlet(Request $request)
    {
      $options = ProjectDetail::where('country_head_office_id',$request->selectedValue)->get();
      return $options;

    }
}
