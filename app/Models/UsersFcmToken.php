<?php

namespace App\Models;

use App\Models\Base\UsersFcmToken as BaseUsersFcmToken;

class UsersFcmToken extends BaseUsersFcmToken
{
	protected $hidden = [
		'fcm_token'
	];

    protected $fillable = [
		'user_id',
		'fcm_token'
	];
}
