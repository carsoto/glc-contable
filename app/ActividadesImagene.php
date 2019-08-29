<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ActividadesImagene
 * 
 * @property int $id
 * @property int $islas_actividades_id
 * @property string $imagen
 * @property string $tipo
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\IslasActividade $islas_actividade
 *
 * @package App
 */
class ActividadesImagene extends Eloquent
{
	protected $casts = [
		'islas_actividades_id' => 'int'
	];

	protected $fillable = [
		'islas_actividades_id',
		'imagen',
		'tipo'
	];

	public function islas_actividade()
	{
		return $this->belongsTo(\App\IslasActividade::class, 'islas_actividades_id');
	}
}
