<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EmbarcacionItinerario
 * 
 * @property int $id
 * @property int $embarcacion_id
 * @property int $itinerarios_id
 * @property int $islas_puntos_visita_id
 * @property int $orden
 * @property int $dias_id
 * @property string $hora
 * 
 * @property \App\Embarcacion $embarcacion
 * @property \App\Itinerario $itinerario
 * @property \App\Dia $dia
 * @property \App\IslasPuntosVisitum $islas_puntos_visitum
 *
 * @package App
 */
class EmbarcacionItinerario extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'embarcacion_id' => 'int',
		'itinerarios_id' => 'int',
		'islas_puntos_visita_id' => 'int',
		'orden' => 'int',
		'dias_id' => 'int'
	];

	protected $fillable = [
		'embarcacion_id',
		'itinerarios_id',
		'islas_puntos_visita_id',
		'orden',
		'dias_id',
		'hora'
	];

	public function embarcacion()
	{
		return $this->belongsTo(\App\Embarcacion::class);
	}

	public function itinerario()
	{
		return $this->belongsTo(\App\Itinerario::class, 'itinerarios_id');
	}

	public function dia()
	{
		return $this->belongsTo(\App\Dia::class, 'dias_id');
	}

	public function islas_puntos_visitum()
	{
		return $this->belongsTo(\App\IslasPuntosVisitum::class, 'islas_puntos_visita_id');
	}
}
