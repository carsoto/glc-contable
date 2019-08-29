<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ChartersEmbarcacion
 * 
 * @property int $id
 * @property int $charters_id
 * @property int $embarcacion_id
 * @property int $itinerarios_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Itinerario $itinerario
 * @property \App\Charter $charter
 * @property \App\Embarcacion $embarcacion
 *
 * @package App
 */
class ChartersEmbarcacion extends Eloquent
{
	protected $table = 'charters_embarcacion';

	protected $casts = [
		'charters_id' => 'int',
		'embarcacion_id' => 'int',
		'itinerarios_id' => 'int'
	];

	protected $fillable = [
		'charters_id',
		'embarcacion_id',
		'itinerarios_id'
	];

	public function itinerario()
	{
		return $this->belongsTo(\App\Itinerario::class, 'itinerarios_id');
	}

	public function charter()
	{
		return $this->belongsTo(\App\Charter::class, 'charters_id');
	}

	public function embarcacion()
	{
		return $this->belongsTo(\App\Embarcacion::class);
	}
}
