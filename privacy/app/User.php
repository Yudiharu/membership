<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kodeine\Acl\Traits\HasRole;
use Laratrust\Traits\LaratrustUserTrait;
use App\Models\Company;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','kode_company',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //protected $appends  = ['nama_company'];

    public function company()
    {
        return $this->belongsTo(Company::class,'kode_company');
    }

    public function getDestroyUrlAttribute()
    {
        return route('users.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('users.edit', $this->id);
    }

    // public function getNamaCompanyAttribute()
    // {
    //     return $this->company();
    // }

//    public function setPasswordAttribute($value)
//    {
//        if (! empty($value)){
//           return  $this->attributes['password'] = bcrypt($value);
//        }
//
//        return false;
//    }

    public function isAdmin()
    {
        $cek_superadmin = $this->roles;
        return $cek_superadmin;
        if ($cek_superadmin > 0){
            return true;
        }
        return false;
    }
}
