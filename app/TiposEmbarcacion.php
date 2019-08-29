<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TiposEmbarcacion
 * 
 * @property int $id
 * @property string $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $modelos_embarcacions
 *
 * @package App
 */
class TiposEmbarcacion extends Eloquent
{
	protected $table = 'tipos_embarcacion';

	protected $fillable = [
		'descripcion'
	];

	public function modelos_embarcacions()
	{
		return $this->hasMany(\App\ModelosEmbarcacion::class);
	}
}