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

    public function segmentations()
    {
        return $this->belongsToMany(MedmasterySegmentation::class, 'segmentation_access_roles', 'access_role_id', 'medmastery_segmentation_id');
    }
}
