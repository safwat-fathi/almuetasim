<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $fillable = [
		'name',
		'email',
		'phone',
		'message',
		'read'
	];

	protected function casts(): array
	{
		return [
			'read' => 'boolean'
		];
	}
}
