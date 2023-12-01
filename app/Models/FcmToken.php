<?php

namespace App\Models;

use App\Models\Base\FcmToken as BaseFcmToken;

class FcmToken extends BaseFcmToken
{
	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'token',
        'device_type'
	];
}
