<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TiposPatente
 * 
 * @property int $id
 * @property string $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $embarcacions
 * @property \Illuminate\Database\Eloquent\Collection $variacion_patentes
 *
 * @package App
 */
class TiposPatente extends Eloquent
{
	protected $table = 'tipos_patente';

	protected $fillable = [
		'descripcion'
	];

	public function embarcacions()
	{
		return $this->hasMany(\App\Embarcacion::class);
	}

	public function variacion_patentes()
	{
		return $this->hasMany(\App\VariacionPatente::class);
	}
}
