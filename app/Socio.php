<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 14 Aug 2019 21:47:07 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Socio
 * 
 * @property int $id
 * @property string $nombre
 * @property int $porcentaje
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $comisiones
 * @property \Illuminate\Database\Eloquent\Collection $socios_regla_negocios
 *
 * @package App
 */
class Socio extends Eloquent
{
	protected $casts = [
		'porcentaje' => 'int'
	];

	protected $fillable = [
		'nombre',
		'porcentaje'
	];

	public function comisiones()
	{
		return $this->hasMany(\App\Comisione::class, 'socios_id');
	}

	public function socios_regla_negocios()
	{
		return $this->hasMany(\App\SociosReglaNegocio::class, 'socios_id');
	}
}
