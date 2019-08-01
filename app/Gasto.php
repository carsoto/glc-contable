<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 30 Jul 2019 19:30:24 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Gasto
 * 
 * @property int $id
 * @property int $charters_id
 * @property int $registrado_por
 * @property string $razon_social
 * @property string $categoria
 * @property float $precio_cliente
 * @property float $neto
 * @property float $ganancia
 * @property int $tipo_gasto_id
 * @property \Carbon\Carbon $fecha
 * @property string $comentario
 * @property string $banco
 * @property string $referencia
 * @property string $tipo_recibo
 * @property string $link_papeleta_pago
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Charter $charter
 * @property \App\TipoGasto $tipo_gasto
 * @property \App\User $user
 *
 * @package App
 */
class Gasto extends Eloquent
{
	protected $casts = [
		'charters_id' => 'int',
		'registrado_por' => 'int',
		'precio_cliente' => 'float',
		'neto' => 'float',
		'ganancia' => 'float',
		'tipo_gasto_id' => 'int'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'charters_id',
		'registrado_por',
		'razon_social',
		'categoria',
		'precio_cliente',
		'neto',
		'ganancia',
		'tipo_gasto_id',
		'fecha',
		'comentario',
		'banco',
		'referencia',
		'tipo_recibo',
		'link_papeleta_pago'
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
		return $this->belongsTo(\App\User::class, 'registrado_por');
	}
}
