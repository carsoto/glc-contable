<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Aug 2019 21:05:15 +0000.
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
 * @property string $tipo
 * @property string $yacht
 * @property string $cliente
 * @property int $brokers_id
 * @property string $broker
 * @property \Carbon\Carbon $fecha_inicio
 * @property \Carbon\Carbon $fecha_fin
 * @property int $programa_id
 * @property int $anyo
 * @property int $mes
 * @property float $precio_venta
 * @property float $yacht_rack
 * @property float $neto
 * @property int $porcentaje_comision_broker
 * @property float $comision_broker
 * @property float $costo_deluxe
 * @property float $comision_glc
 * @property float $apa
 * @property string $contrato
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Programa $programa
 * @property \Illuminate\Database\Eloquent\Collection $comisiones
 * @property \Illuminate\Database\Eloquent\Collection $entradas
 * @property \Illuminate\Database\Eloquent\Collection $gastos
 * @property \Illuminate\Database\Eloquent\Collection $historials
 *
 * @package App
 */
class Charter extends Eloquent
{
	protected $casts = [
		'creado_por' => 'int',
		'brokers_id' => 'int',
		'programa_id' => 'int',
		'anyo' => 'int',
		'mes' => 'int',
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
		'tipo',
		'yacht',
		'cliente',
		'brokers_id',
		'broker',
		'fecha_inicio',
		'fecha_fin',
		'programa_id',
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
		'contrato',
		'status'
	];

	public function broker()
	{
		return $this->belongsTo(\App\Broker::class, 'brokers_id');
	}

	public function programa()
	{
		return $this->belongsTo(\App\Programa::class);
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

	public function historials()
	{
		return $this->hasMany(\App\Historial::class, 'charters_id');
	}
}
