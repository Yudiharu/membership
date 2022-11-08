<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kodeine\Acl\Traits\HasPermission;
use Kodeine\Acl\Models\Eloquent\Role as RoleModel;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
//    use HasPermission;

    protected $fillable = [
        'id', 'name', 'display_name', 'description'
    ];

    public function getDestroyUrlAttribute()
    {
        return route('roles.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('roles.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('roles.update',$this->id);
    }

    public function getRouteKeyName() {
        return 'id';
    }
}
