<?php

namespace App\Models;

use App\Notifications\SendTwoFactorCodeNotification;
use App\Traits\HasRole;
use App\Traits\HasEncrypt;
use App\Traits\HasMfa;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRole, HasEncrypt, HasMfa;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'password',
        'profile_picture_path',
        'status',
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var list<string>
     */
    protected $encryptable = ['name', 'email', 'phone'];

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
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['avatar'];

    public function twoFactorAuthEnabled(): bool
    {
        return in_array(HasMfa::class, class_uses_recursive($this)) && $this->mfa_enabled;
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

    public function getAvatarAttribute(): string
    {
        if ($this->profile_picture_path != null) {
            return asset('storage/' . $this->profile_picture_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=ffffff&background=1c87c9';
    }
}
