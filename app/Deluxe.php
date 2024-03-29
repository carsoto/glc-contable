<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 25 Jul 2019 16:20:07 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Deluxe
 * 
 * @property int $id
 * @property int $users_id
 * @property int $charters_id
 * @property float $monto
 * @property \Carbon\Carbon $fecha
 * @property string $comentario
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Charter $charter
 * @property \App\User $user
 *
 * @package App
 */
class Deluxe extends Eloquent
{
	protected $table = 'deluxe';

	protected $casts = [
		'users_id' => 'int',
		'charters_id' => 'int',
		'monto' => 'float'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'users_id',
		'charters_id',
		'monto',
		'fecha',
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
