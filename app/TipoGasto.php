<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 25 Jul 2019 16:20:07 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TipoGasto
 * 
 * @property int $id
 * @property string $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $entradas
 * @property \Illuminate\Database\Eloquent\Collection $gastos
 *
 * @package App
 */
class TipoGasto extends Eloquent
{
	protected $table = 'tipo_gasto';

	protected $fillable = [
		'descripcion'
	];

	public function entradas()
	{
		return $this->hasMany(\App\Entrada::class);
	}

	public function gastos()
	{
		return $this->hasMany(\App\Gasto::class);
	}
}
