<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionAccessRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_role_id',
        'route',
    ];

    /**
     * Relasi ke model AccessRole.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accessRole()
    {
        return $this->belongsTo(AccessRole::class, 'access_role_id');
    }
}
