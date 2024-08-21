<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class News
 * 
 * @property int $id
 * @property int $created_by
 * @property string $title
 * @property string $content
 * @property string|null $image
 * @property int|null $image_id
 * 
 * @property User $user
 * @property File|null $file
 *
 * @package App\Models
 */
class News extends Model
{
	protected $table = 'news';
	public $timestamps = false;

	protected $casts = [
		'created_by' => 'int',
		'image_id' => 'int'
	];

	protected $fillable = [
		'created_by',
		'title',
		'content',
		'image',
		'image_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function file()
	{
		return $this->belongsTo(File::class, 'image_id');
	}
}
