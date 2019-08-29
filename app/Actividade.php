<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Actividade
 * 
 * @property int $id
 * @property string $tipo
 * @property string $nombre
 * @property string $descripcion
 * @property string $abreviatura
 * @property int $max_personas
 * @property string $precio
 * @property string $categoria
 * @property string $imagen
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $islas
 * @property \Illuminate\Database\Eloquent\Collection $puntos_visita_actividades
 *
 * @package App
 */
class Actividade extends Eloquent
{
	protected $casts = [
		'max_personas' => 'int'
	];

	protected $fillable = [
		'tipo',
		'nombre',
		'descripcion',
		'abreviatura',
		'max_personas',
		'precio',
		'categoria',
		'imagen'
	];

	public function islas()
	{
		return $this->belongsToMany(\App\Isla::class, 'islas_actividades', 'actividades_id', 'islas_id')
					->withPivot('id')
					->withTimestamps();
	}

	public function puntos_visita_actividades()
	{
		return $this->hasMany(\App\PuntosVisitaActividade::class, 'actividades_id');
	}
}
