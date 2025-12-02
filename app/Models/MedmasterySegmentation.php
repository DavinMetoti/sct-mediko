<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MedmasterySegmentation extends Model
{
    protected $table = 'medmastery_segmentation';

    protected $fillable = [
        'name',
        'description',
        'color',
        'created_by',
    ];

    public function categories()
    {
        return $this->hasMany(MedmasteryCategory::class, 'medmastery_segmentation_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accessRoles()
    {
        return $this->belongsToMany(AccessRole::class, 'segmentation_access_roles', 'medmastery_segmentation_id', 'access_role_id');
    }

    public function allowedUsers()
    {
        return $this->belongsToMany(User::class, 'segmentation_users', 'medmastery_segmentation_id', 'user_id');
    }
}
