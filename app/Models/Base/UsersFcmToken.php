<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersFcmToken
 *
 * @property int $user_id
 * @property string $fcm_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Base
 */
class UsersFcmToken extends Model
{
	protected $table = 'users_fcm_tokens';
	public $incrementing = false;

	protected $casts = [
		'user_id' => 'int'
	];
}
