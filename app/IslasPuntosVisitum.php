<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 06 Sep 2019 16:59:06 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class IslasPuntosVisitum
 * 
 * @property int $id
 * @property int $islas_id
 * @property int $puntos_visita_id
 * 
 * @property \App\Isla $isla
 * @property \App\PuntosVisitum $puntos_visitum
 * @property \Illuminate\Database\Eloquent\Collection $embarcacion_itinerarios
 *
 * @package App
 */
class IslasPuntosVisitum extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'islas_id' => 'int',
		'puntos_visita_id' => 'int'
	];

	protected $fillable = [
		'islas_id',
		'puntos_visita_id'
	];

	public function isla()
	{
		return $this->belongsTo(\App\Isla::class, 'islas_id');
	}

	public function puntos_visitum()
	{
		return $this->belongsTo(\App\PuntosVisitum::class, 'puntos_visita_id');
	}

	public function embarcacion_itinerarios()
	{
		return $this->hasMany(\App\EmbarcacionItinerario::class, 'islas_puntos_visita_id');
	}
}
