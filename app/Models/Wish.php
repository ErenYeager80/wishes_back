<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Wish
 * 
 * @property int $id
 * @property int $user_id
 * @property int|null $image_id
 * @property string $content
 * @property string $title
 * @property Carbon|null $done_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property File|null $file
 * @property User $user
 *
 * @package App\Models
 */
class Wish extends Model
{
	protected $table = 'wishes';

	protected $casts = [
		'user_id' => 'int',
		'image_id' => 'int',
		'done_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'image_id',
		'content',
		'title',
		'done_at'
	];

	public function file()
	{
		return $this->belongsTo(File::class, 'image_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
