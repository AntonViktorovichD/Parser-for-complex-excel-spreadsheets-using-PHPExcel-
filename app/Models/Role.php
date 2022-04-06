<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Models\Permission;
use App\Http\Models\User;

class Role extends Model {
    public function permissions() {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }
    public function roles(){
        return $this->belongsToMany(Role::class);
    }

}
