<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 06 Sep 2019 16:59:06 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TipoContacto
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
class TipoContacto extends Eloquent
{
	protected $table = 'tipo_contacto';

	protected $fillable = [
		'descripcion'
	];

	public function pedidos()
	{
		return $this->hasMany(\App\Pedido::class);
	}
}
