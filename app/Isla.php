<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Isla
 * 
 * @property int $id
 * @property string $nombre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $actividades
 * @property \Illuminate\Database\Eloquent\Collection $islas_puntos_visita
 * @property \Illuminate\Database\Eloquent\Collection $puertos
 * @property \Illuminate\Database\Eloquent\Collection $puntos_visita_actividades
 *
 * @package App
 */
class Isla extends Eloquent
{
	protected $fillable = [
		'nombre'
	];

	public function actividades()
	{
		return $this->belongsToMany(\App\Actividade::class, 'islas_actividades', 'islas_id', 'actividades_id')
					->withPivot('id')
					->withTimestamps();
	}

	public function islas_puntos_visita()
	{
		return $this->hasMany(\App\IslasPuntosVisitum::class, 'islas_id');
	}

	public function puertos()
	{
		return $this->hasMany(\App\Puerto::class, 'islas_id');
	}

	public function puntos_visita_actividades()
	{
		return $this->hasMany(\App\PuntosVisitaActividade::class, 'islas_id');
	}
}
