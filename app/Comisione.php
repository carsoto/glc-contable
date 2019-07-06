<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 06 Jul 2019 01:22:36 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Comisione
 * 
 * @property int $id
 * @property int $users_id
 * @property int $charters_id
 * @property int $socios_id
 * @property int $porcentaje_comision_socio
 * @property float $monto
 * @property float $abonado
 * @property float $saldo
 * @property \Carbon\Carbon $fecha_ult_abono
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Charter $charter
 * @property \App\Socio $socio
 * @property \App\User $user
 * @property \Illuminate\Database\Eloquent\Collection $abonos_comisiones
 *
 * @package App
 */
class Comisione extends Eloquent
{
	protected $casts = [
		'users_id' => 'int',
		'charters_id' => 'int',
		'socios_id' => 'int',
		'porcentaje_comision_socio' => 'int',
		'monto' => 'float',
		'abonado' => 'float',
		'saldo' => 'float'
	];

	protected $dates = [
		'fecha_ult_abono'
	];

	protected $fillable = [
		'users_id',
		'charters_id',
		'socios_id',
		'porcentaje_comision_socio',
		'monto',
		'abonado',
		'saldo',
		'fecha_ult_abono'
	];

	public function charter()
	{
		return $this->belongsTo(\App\Charter::class, 'charters_id');
	}

	public function socio()
	{
		return $this->belongsTo(\App\Socio::class, 'socios_id');
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'users_id');
	}

	public function abonos_comisiones()
	{
		return $this->hasMany(\App\AbonosComisione::class, 'comisiones_id');
	}
}
