<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FcmToken
 *
 * @property int $id
 * @property string $token
 * @property string|null $device_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Base
 */
class FcmToken extends Model
{
	protected $table = 'fcm_token';
}
