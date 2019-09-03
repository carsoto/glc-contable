<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 30 Aug 2019 21:47:27 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PedidosStatus
 * 
 * @property int $id
 * @property string $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $pedidos
 *
 * @package App
 */
class PedidosStatus extends Eloquent
{
	protected $table = 'pedidos_status';

	protected $fillable = [
		'descripcion'
	];

	public function pedidos()
	{
		return $this->hasMany(\App\Pedido::class);
	}
}
