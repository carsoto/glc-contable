<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 14 Aug 2019 21:47:07 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class AbonosComisione
 * 
 * @property int $id
 * @property int $users_id
 * @property int $comisiones_id
 * @property float $monto
 * @property \Carbon\Carbon $fecha
 * @property string $comentario
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Comisione $comisione
 * @property \App\User $user
 *
 * @package App
 */
class AbonosComisione extends Eloquent
{
	protected $casts = [
		'users_id' => 'int',
		'comisiones_id' => 'int',
		'monto' => 'float'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'users_id',
		'comisiones_id',
		'monto',
		'fecha',
		'comentario'
	];

	public function comisione()
	{
		return $this->belongsTo(\App\Comisione::class, 'comisiones_id');
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'users_id');
	}
}
