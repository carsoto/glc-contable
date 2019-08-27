<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Aug 2019 21:05:15 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PuntosVisitaActividade
 * 
 * @property int $id
 * @property int $islas_id
 * @property int $puntos_visita_id
 * @property int $actividades_id
 * 
 * @property \App\Isla $isla
 * @property \App\Actividade $actividade
 * @property \App\PuntosVisitum $puntos_visitum
 *
 * @package App
 */
class PuntosVisitaActividade extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'islas_id' => 'int',
		'puntos_visita_id' => 'int',
		'actividades_id' => 'int'
	];

	protected $fillable = [
		'islas_id',
		'puntos_visita_id',
		'actividades_id'
	];

	public function isla()
	{
		return $this->belongsTo(\App\Isla::class, 'islas_id');
	}

	public function actividade()
	{
		return $this->belongsTo(\App\Actividade::class, 'actividades_id');
	}

	public function puntos_visitum()
	{
		return $this->belongsTo(\App\PuntosVisitum::class, 'puntos_visita_id');
	}
}
