<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 06 Jul 2019 01:22:36 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Charter
 * 
 * @property int $id
 * @property int $creado_por
 * @property string $codigo
 * @property string $descripcion
 * @property string $yacht
 * @property string $broker
 * @property \Carbon\Carbon $fecha_inicio
 * @property \Carbon\Carbon $fecha_fin
 * @property string $anyo
 * @property string $mes
 * @property float $precio_venta
 * @property float $yacht_rack
 * @property float $neto
 * @property int $porcentaje_comision_broker
 * @property float $comision_broker
 * @property float $costo_deluxe
 * @property float $comision_glc
 * @property float $apa
 * @property string $contrato
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\User $user
 * @property \Illuminate\Database\Eloquent\Collection $comisiones
 * @property \Illuminate\Database\Eloquent\Collection $entradas
 * @property \Illuminate\Database\Eloquent\Collection $gastos
 *
 * @package App
 */
class Charter extends Eloquent
{
	protected $casts = [
		'creado_por' => 'int',
		'precio_venta' => 'float',
		'yacht_rack' => 'float',
		'neto' => 'float',
		'porcentaje_comision_broker' => 'int',
		'comision_broker' => 'float',
		'costo_deluxe' => 'float',
		'comision_glc' => 'float',
		'apa' => 'float'
	];

	protected $dates = [
		'fecha_inicio',
		'fecha_fin'
	];

	protected $fillable = [
		'creado_por',
		'codigo',
		'descripcion',
		'yacht',
		'broker',
		'fecha_inicio',
		'fecha_fin',
		'anyo',
		'mes',
		'precio_venta',
		'yacht_rack',
		'neto',
		'porcentaje_comision_broker',
		'comision_broker',
		'costo_deluxe',
		'comision_glc',
		'apa',
		'contrato'
	];

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'creado_por');
	}

	public function comisiones()
	{
		return $this->hasMany(\App\Comisione::class, 'charters_id');
	}

	public function entradas()
	{
		return $this->hasMany(\App\Entrada::class, 'charters_id');
	}

	public function gastos()
	{
		return $this->hasMany(\App\Gasto::class, 'charters_id');
	}
}
