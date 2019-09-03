<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 30 Aug 2019 21:47:27 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Itinerario
 * 
 * @property int $id
 * @property string $nombre
 * @property string $url_imagen
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $charters_embarcacions
 * @property \Illuminate\Database\Eloquent\Collection $embarcacions
 *
 * @package App
 */
class Itinerario extends Eloquent
{
	protected $fillable = [
		'nombre',
		'url_imagen'
	];

	public function charters_embarcacions()
	{
		return $this->hasMany(\App\ChartersEmbarcacion::class, 'itinerarios_id');
	}

	public function embarcacions()
	{
		return $this->belongsToMany(\App\Embarcacion::class, 'embarcacion_itinerarios', 'itinerarios_id')
					->withPivot('id', 'islas_puntos_visita_id', 'orden', 'dias_id', 'hora');
	}
}
