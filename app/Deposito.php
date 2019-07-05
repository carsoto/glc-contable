<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 25 Jun 2019 21:51:04 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Deposito
 * 
 * @property int $id
 * @property int $users_id
 * @property string $razon_social
 * @property float $monto
 * @property \Carbon\Carbon $fecha
 * @property string $comentario
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $charters_id
 * 
 * @property \App\Charter $charter
 * @property \App\User $user
 *
 * @package App
 */
class Deposito extends Eloquent
{
	protected $casts = [
		'users_id' => 'int',
		'monto' => 'float',
		'charters_id' => 'int'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'users_id',
		'razon_social',
		'monto',
		'fecha',
		'comentario',
		'charters_id'
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
