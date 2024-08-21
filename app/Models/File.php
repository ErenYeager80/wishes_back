<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * 
 * @property int $id
 * @property string $path
 * @property int $user_id
 * @property string $hash
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|News[] $news
 * @property Collection|Wish[] $wishes
 *
 * @package App\Models
 */
class File extends Model
{
	protected $table = 'files';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'path',
		'user_id',
		'hash'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function news()
	{
		return $this->hasMany(News::class, 'image_id');
	}

	public function wishes()
	{
		return $this->hasMany(Wish::class, 'image_id');
	}
}
