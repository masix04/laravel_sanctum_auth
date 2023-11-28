<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Base\User as BaseUser;

class User extends Authenticatable
{
	use HasApiTokens;

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'dob',
		'email_verified_at',
		'password',
		'remember_token'
	];
}
