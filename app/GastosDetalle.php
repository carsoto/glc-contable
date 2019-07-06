<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 06 Jul 2019 01:22:36 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GastosDetalle
 * 
 * @property int $id
 * @property int $users_id
 * @property int $gastos_id
 * @property float $monto
 * @property \Carbon\Carbon $fecha
 * @property string $comentario
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Gasto $gasto
 * @property \App\User $user
 *
 * @package App
 */
class GastosDetalle extends Eloquent
{
	protected $casts = [
		'users_id' => 'int',
		'gastos_id' => 'int',
		'monto' => 'float'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'users_id',
		'gastos_id',
		'monto',
		'fecha',
		'comentario'
	];

	public function gasto()
	{
		return $this->belongsTo(\App\Gasto::class, 'gastos_id');
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'users_id');
	}
}
