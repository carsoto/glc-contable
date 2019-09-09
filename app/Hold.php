<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 06 Sep 2019 16:59:06 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Hold
 * 
 * @property int $id
 * @property \Carbon\Carbon $f_inicio
 * @property \Carbon\Carbon $f_fin
 * @property string $status
 * @property int $yacht
 * @property int $itinerario_id
 * @property float $tarifa
 * @property string $comentario
 * @property \Carbon\Carbon $f_limite
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Embarcacion $embarcacion
 * @property \App\Itinerario $itinerario
 *
 * @package App
 */
class Hold extends Eloquent
{
	protected $casts = [
		'yacht' => 'int',
		'itinerario_id' => 'int',
		'tarifa' => 'float'
	];

	protected $dates = [
		'f_inicio',
		'f_fin',
		'f_limite'
	];

	protected $fillable = [
		'f_inicio',
		'f_fin',
		'status',
		'yacht',
		'itinerario_id',
		'tarifa',
		'comentario',
		'f_limite'
	];

	public function embarcacion()
	{
		return $this->belongsTo(\App\Embarcacion::class, 'yacht');
	}

	public function itinerario()
	{
		return $this->belongsTo(\App\Itinerario::class);
	}
}
