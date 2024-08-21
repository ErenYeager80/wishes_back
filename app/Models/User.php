<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property Carbon|null $email_verified_at
 * @property string|null $otp_code
 * @property Carbon|null $otp_expires_at
 * @property string $phone
 * @property string|null $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|File[] $files
 * @property Collection|News[] $news
 * @property Collection|Wish[] $wishes
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime',
		'otp_expires_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
        'role',
		'email_verified_at',
		'otp_code',
		'otp_expires_at',
		'phone',
		'password',
		'remember_token'
	];

	public function files()
	{
		return $this->hasMany(File::class);
	}

	public function news()
	{
		return $this->hasMany(News::class, 'created_by');
	}

	public function wishes()
	{
		return $this->hasMany(Wish::class);
	}

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
