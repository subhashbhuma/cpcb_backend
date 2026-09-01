<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity, AuthenticationLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'profile_image',
        'name',
        'email',
        'mobile_number',
        'email_verified_at',
        'password',
        'session_id',
        'created_by',
        'updated_by',
        'remember_token',
        'deleted_at',
        'created_at',
        'updated_at',
        'current_session_id',
        'lockout_until',
        'failed_logins',
        'designation_id',
        'emp_code',
        'level',
        'cell',
        'posted_at',
        'pan_no',
        'division_id',
        'password_changed_at',
        'password_expires_at',
        'force_password_change'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'lockout_until' => 'datetime',
            'password_changed_at' => 'datetime',
            'password_expires_at' => 'datetime',
            'force_password_change' => 'boolean',
        ];
    }

    // 📝 Spatie activity log configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // logs all fillable attributes
            ->useLogName('user') // label used in the activity_log table
            ->logOnlyDirty() // only log changes (not unchanged updates)
            ->setDescriptionForEvent(fn(string $eventName) => "User model has been {$eventName}");
    }

    



    /**
     * Check if logged in from another session
     */
    public function isLoggedInElsewhere(?string $sessionId = null): bool
    {
        $sessionId = $sessionId ?: session()->getId();

        return !empty($this->current_session_id)
            && $this->current_session_id !== $sessionId;
    }

    /**
     * Store current session ID (on successful login)
     */
    public function updateSessionInfo(?string $sessionId = null): void
    {
        $this->update([
            'current_session_id' => $sessionId ?: session()->getId(),
            'failed_logins' => 0,
            'lockout_until' => null,
        ]);
    }

    /**
     * Clear session (on logout / expiry / forced logout)
     */
    public function clearSessionInfo(): void
    {
        $this->update([
            'current_session_id' => null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Lockout & Security
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user is locked
     */
    public function isLockedOut(): bool
    {
        return $this->lockout_until
            && now()->lessThan($this->lockout_until);
    }

    /**
     * Register failed login attempt
     */
    public function registerFailedLogin(): void
    {
        $this->increment('failed_logins');

        if ($this->failed_logins >= 5) {
            $this->update([
                'lockout_until' => now()->addHour(),
            ]);
        }
    }

    /**
     * Reset failed attempts
     */
    public function resetLoginAttempts(): void
    {
        $this->update([
            'failed_logins' => 0,
            'lockout_until' => null,
        ]);
    }

    /**
     * Get the designation for the user
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }

    /**
     * Get the division for the user
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    /**
     * Get the password histories for the user
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }
}

