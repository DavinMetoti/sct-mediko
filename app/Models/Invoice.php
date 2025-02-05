<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'user_id',
        'package_id',
        'status',
        'payment_proof',
    ];

    /**
     * Invoice status options
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_VERIFICATION = 'verification';

    /**
     * Define relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define relationship with Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
