<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 06 Jul 2019 01:22:36 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Gasto
 * 
 * @property int $id
 * @property int $users_id
 * @property int $charters_id
 * @property int $tipo_gasto_id
 * @property float $total
 * @property float $gastos
 * @property float $saldo
 * @property \Carbon\Carbon $fecha_ult_abono
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Charter $charter
 * @property \App\TipoGasto $tipo_gasto
 * @property \App\User $user
 * @property \Illuminate\Database\Eloquent\Collection $gastos_detalles
 *
 * @package App
 */
class Gasto extends Eloquent
{
	protected $casts = [
		'users_id' => 'int',
		'charters_id' => 'int',
		'tipo_gasto_id' => 'int',
		'total' => 'float',
		'gastos' => 'float',
		'saldo' => 'float'
	];

	protected $dates = [
		'fecha_ult_abono'
	];

	protected $fillable = [
		'users_id',
		'charters_id',
		'tipo_gasto_id',
		'total',
		'gastos',
		'saldo',
		'fecha_ult_abono'
	];

	public function charter()
	{
		return $this->belongsTo(\App\Charter::class, 'charters_id');
	}

	public function tipo_gasto()
	{
		return $this->belongsTo(\App\TipoGasto::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'users_id');
	}

	public function gastos_detalles()
	{
		return $this->hasMany(\App\GastosDetalle::class, 'gastos_id');
	}
}
