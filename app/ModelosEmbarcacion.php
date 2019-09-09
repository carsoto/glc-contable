<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 06 Sep 2019 16:59:06 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ModelosEmbarcacion
 * 
 * @property int $id
 * @property string $descripcion
 * @property int $tipos_embarcacion_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\TiposEmbarcacion $tipos_embarcacion
 * @property \Illuminate\Database\Eloquent\Collection $embarcacions
 *
 * @package App
 */
class ModelosEmbarcacion extends Eloquent
{
	protected $table = 'modelos_embarcacion';

	protected $casts = [
		'tipos_embarcacion_id' => 'int'
	];

	protected $fillable = [
		'descripcion',
		'tipos_embarcacion_id'
	];

	public function tipos_embarcacion()
	{
		return $this->belongsTo(\App\TiposEmbarcacion::class);
	}

	public function embarcacions()
	{
		return $this->hasMany(\App\Embarcacion::class);
	}
}
