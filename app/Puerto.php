<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Puerto
 * 
 * @property int $id
 * @property string $descripcion
 * @property string $provincia
 * @property int $islas_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Isla $isla
 * @property \Illuminate\Database\Eloquent\Collection $embarcacions
 *
 * @package App
 */
class Puerto extends Eloquent
{
	protected $casts = [
		'islas_id' => 'int'
	];

	protected $fillable = [
		'descripcion',
		'provincia',
		'islas_id'
	];

	public function isla()
	{
		return $this->belongsTo(\App\Isla::class, 'islas_id');
	}

	public function embarcacions()
	{
		return $this->hasMany(\App\Embarcacion::class, 'puerto_registro_id');
	}
}
