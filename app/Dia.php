<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Aug 2019 21:05:15 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Dia
 * 
 * @property int $id
 * @property string $dia
 * @property string $abrev_es
 * @property string $dia_en
 * @property string $abrev_en
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $embarcacion_itinerarios
 *
 * @package App
 */
class Dia extends Eloquent
{
	protected $fillable = [
		'dia',
		'abrev_es',
		'dia_en',
		'abrev_en'
	];

	public function embarcacion_itinerarios()
	{
		return $this->hasMany(\App\EmbarcacionItinerario::class, 'dias_id');
	}
}
