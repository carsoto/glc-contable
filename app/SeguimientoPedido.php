<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 30 Aug 2019 21:47:27 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class SeguimientoPedido
 * 
 * @property int $id
 * @property int $users_id
 * @property int $pedidos_id
 * @property \Carbon\Carbon $fecha
 * @property string $comentario
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Pedido $pedido
 * @property \App\User $user
 *
 * @package App
 */
class SeguimientoPedido extends Eloquent
{
	protected $casts = [
		'users_id' => 'int',
		'pedidos_id' => 'int'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'users_id',
		'pedidos_id',
		'fecha',
		'comentario'
	];

	public function pedido()
	{
		return $this->belongsTo(\App\Pedido::class, 'pedidos_id');
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'users_id');
	}
}
