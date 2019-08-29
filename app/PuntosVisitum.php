<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PuntosVisitum
 * 
 * @property int $id
 * @property string $punto
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $islas_puntos_visita
 * @property \Illuminate\Database\Eloquent\Collection $puntos_visita_actividades
 *
 * @package App
 */
class PuntosVisitum extends Eloquent
{
	protected $fillable = [
		'punto'
	];

	public function islas_puntos_visita()
	{
		return $this->hasMany(\App\IslasPuntosVisitum::class, 'puntos_visita_id');
	}

	public function puntos_visita_actividades()
	{
		return $this->hasMany(\App\PuntosVisitaActividade::class, 'puntos_visita_id');
	}
}
