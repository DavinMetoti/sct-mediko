<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'id_access_role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',  // Ensure password is hashed
    ];

    /**
     * The attributes that should be converted to JSON.
     *
     * @var array
     */
    protected $appends = [
        'is_active', // You could append extra fields like active status
    ];

    /**
     * Get the user's active status (Example: Custom Accessors).
     *
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return $this->is_actived === 1;
    }

    /**
     * Validation rules for creating or updating users.
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username|max:255',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed', // Password validation
        ];
    }

    /**
     * Scope a query to filter only active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_actived', 1);
    }

    public function accessRole()
    {
        return $this->belongsTo(AccessRole::class, 'id_access_role');
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class, 'id_users', 'id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_user', 'user_id', 'package_id');
    }
}
