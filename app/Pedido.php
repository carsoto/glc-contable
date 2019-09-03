<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 30 Aug 2019 21:47:27 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Pedido
 * 
 * @property int $id
 * @property int $tipo_contacto_id
 * @property \Carbon\Carbon $fecha
 * @property string $compania
 * @property string $solicitante
 * @property string $telefono
 * @property string $email
 * @property \Carbon\Carbon $f_inicio
 * @property \Carbon\Carbon $f_fin
 * @property \Carbon\Carbon $prox_seguimiento
 * @property string $detalles
 * @property int $pedidos_status_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\PedidosStatus $pedidos_status
 * @property \App\TipoContacto $tipo_contacto
 * @property \Illuminate\Database\Eloquent\Collection $seguimiento_pedidos
 *
 * @package App
 */
class Pedido extends Eloquent
{
	protected $casts = [
		'tipo_contacto_id' => 'int',
		'pedidos_status_id' => 'int'
	];

	protected $dates = [
		'fecha',
		'f_inicio',
		'f_fin',
		'prox_seguimiento'
	];

	protected $fillable = [
		'tipo_contacto_id',
		'fecha',
		'compania',
		'solicitante',
		'telefono',
		'email',
		'f_inicio',
		'f_fin',
		'prox_seguimiento',
		'detalles',
		'pedidos_status_id'
	];

	public function pedidos_status()
	{
		return $this->belongsTo(\App\PedidosStatus::class);
	}

	public function tipo_contacto()
	{
		return $this->belongsTo(\App\TipoContacto::class);
	}

	public function seguimiento_pedidos()
	{
		return $this->hasMany(\App\SeguimientoPedido::class, 'pedidos_id');
	}
}
