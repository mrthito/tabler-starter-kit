<?php

namespace App\Models;

use App\Notifications\SendTwoFactorCodeNotification;
use App\Traits\HasRole;
use App\Traits\HasEncrypt;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRole, HasEncrypt;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var list<string>
     */
    protected $encryptable = ['name'];

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function twoFactorAuthEnabled(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function sendTwoFactorAuthCode(): void
    {
        $code = rand(100000, 999999);

        // Delete any existing codes for this user
        DB::table('password_reset_tokens')->where('email', 'admin::' . $this->email)->delete();

        // Create a new code record
        DB::table('password_reset_tokens')->insert([
            'email' => 'admin::' . $this->email,
            'token' => Hash::make($code),
            'created_at' => now(),
        ]);

        // send the code to the user's email
        $this->notify(new SendTwoFactorCodeNotification($code));
    }

    public function verifyTwoFactorAuthCode(string $code): bool
    {
        $token = DB::table('password_reset_tokens')->where('email', 'admin::' . $this->email)->first();
        if (!$token) {
            return false;
        }
        if (!Hash::check($code, $token->token)) {
            return false;
        }

        return $token->created_at > now()->subMinutes(10);
    }
}
