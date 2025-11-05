<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id','employee_id','office_type','country_head_office_id','outlet_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role)
    {
        $uRole=$this->role;

        return $uRole->slug==$role?true:false;
    }
    public function emp()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }

    public function addPermissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission($permission): bool
    {
        $permission_first = $this->role->permissions()->where('slug', $permission)->first();
        if($permission_first){
            return  true;
        }
        else
        {
            return $this->addPermissions->where('slug', $permission)->first() ? true : false;
        }
    }

    public function outlet()
    {
        return $this->belongsTo(ProjectDetail::class , 'outlet_id');
    }
  public function headOffice()
    {
        return $this->belongsTo(CountryHeadOffice::class , 'country_head_office_id');
    }
    public function project()
    {
        return $this->belongsTo(ProjectDetail::class,'outlet_id');
    }
}
