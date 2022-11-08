<?php

namespace App;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{

    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    public function getDestroyUrlAttribute()
    {
        return route('permissions.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('permissions.edit',$this->id);
    }

    public function getShowUrlAttribute()
    {
        return route('permissions.show',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('permissions.update',$this->id);
    }
}
