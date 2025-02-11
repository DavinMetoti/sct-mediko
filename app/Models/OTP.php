<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OTP extends Model
{
    protected $fillable = ['email', 'otp', 'expires_at'];

    protected $table = 'otps';

    public static function generateOtp($email)
    {
        $otp = rand(100000, 999999);

        return self::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5)
            ]
        )->otp;
    }
}
