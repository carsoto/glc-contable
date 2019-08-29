<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class VariacionPatente
 * 
 * @property int $id
 * @property int $tipos_patente_id
 * @property int $primer_orden
 * @property int $segundo_orden
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\TiposPatente $tipos_patente
 *
 * @package App
 */
class VariacionPatente extends Eloquent
{
	protected $table = 'variacion_patente';

	protected $casts = [
		'tipos_patente_id' => 'int',
		'primer_orden' => 'int',
		'segundo_orden' => 'int'
	];

	protected $fillable = [
		'tipos_patente_id',
		'primer_orden',
		'segundo_orden'
	];

	public function tipos_patente()
	{
		return $this->belongsTo(\App\TiposPatente::class);
	}
}
