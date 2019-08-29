<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class IslasActividade
 * 
 * @property int $id
 * @property int $islas_id
 * @property int $actividades_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Isla $isla
 * @property \App\Actividade $actividade
 * @property \Illuminate\Database\Eloquent\Collection $actividades_imagenes
 *
 * @package App
 */
class IslasActividade extends Eloquent
{
	protected $casts = [
		'islas_id' => 'int',
		'actividades_id' => 'int'
	];

	protected $fillable = [
		'islas_id',
		'actividades_id'
	];

	public function isla()
	{
		return $this->belongsTo(\App\Isla::class, 'islas_id');
	}

	public function actividade()
	{
		return $this->belongsTo(\App\Actividade::class, 'actividades_id');
	}

	public function actividades_imagenes()
	{
		return $this->hasMany(\App\ActividadesImagene::class, 'islas_actividades_id');
	}
}
