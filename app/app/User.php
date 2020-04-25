<?php

namespace App;

use App\Jobs\SendNotification;
use App\Notifications\UserAccountCreated;
use App\Notifications\UserPasswordChanged;
use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles, Notifiable, TablePaginate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $searchable = [
        'username',
        'first_name',
        'last_name',
        'email'
    ];

    /*
     |--------------------------------------------------------------------------
     | Accessors
     |--------------------------------------------------------------------------
     |
     */
    public function getAllPermissionsAttribute()
    {
        return $this->getAllPermissions()->pluck('name');
    }

    public function getAllRolesAttribute()
    {
        return $this->getRoleNames();
    }

    /**
     * Generate random password
     */
    public static function generatePassword($length = 32)
    {
        return bcrypt(str_random($length));
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */
    public function roleWithPermissions()
    {
        if ($this->relationLoaded('roles')) {
            $this->setRelation('permissions', $this->roles[0]->permissions->pluck('name')->values());

            $this->setRelation('role', $this->roles->pluck('name'));
            $this->unsetRelation('roles');
        }
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function profileImage(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'profile_image_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @return bool|null
     */
    public function delete(): ?bool
    {
        // Delete profile image
        if ($this->profileImage) {
            $this->profileImage->delete();
        }

        return parent::delete();
    }
}
