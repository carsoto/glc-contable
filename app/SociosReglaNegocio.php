<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 06 Sep 2019 16:59:06 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class SociosReglaNegocio
 * 
 * @property int $id
 * @property int $socios_id
 * @property int $reglas_negocio_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\ReglasNegocio $reglas_negocio
 * @property \App\Socio $socio
 *
 * @package App
 */
class SociosReglaNegocio extends Eloquent
{
	protected $casts = [
		'socios_id' => 'int',
		'reglas_negocio_id' => 'int'
	];

	protected $fillable = [
		'socios_id',
		'reglas_negocio_id'
	];

	public function reglas_negocio()
	{
		return $this->belongsTo(\App\ReglasNegocio::class);
	}

	public function socio()
	{
		return $this->belongsTo(\App\Socio::class, 'socios_id');
	}
}
