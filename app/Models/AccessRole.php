<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRole extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description','access'];

    public function accessRolePermission()
    {
        return $this->hasMany(PermissionAccessRole::class, 'access_role_id');
    }
}
