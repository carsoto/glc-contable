<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 23 Jul 2019 21:22:50 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Historial
 * 
 * @property int $id
 * @property int $users_id
 * @property int $charters_id
 * @property string $item
 * @property string $accion
 * @property string $comentario
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Charter $charter
 * @property \App\User $user
 *
 * @package App
 */
class Historial extends Eloquent
{
	protected $table = 'historial';

	protected $casts = [
		'users_id' => 'int',
		'charters_id' => 'int'
	];

	protected $fillable = [
		'users_id',
		'charters_id',
		'item',
		'accion',
		'comentario'
	];

	public function charter()
	{
		return $this->belongsTo(\App\Charter::class, 'charters_id');
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'users_id');
	}
}
