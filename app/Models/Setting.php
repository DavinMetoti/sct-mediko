<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'value',
        'key',
        'label',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($setting) {
            if (self::where('key', $setting->key)->exists()) {
                throw new \Exception('The key must be unique.');
            }
        });
    }
}
