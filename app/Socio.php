<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 23 Jul 2019 21:22:50 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Socio
 * 
 * @property int $id
 * @property string $nombre
 * @property int $porcentaje
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $comisiones
 *
 * @package App
 */
class Socio extends Eloquent
{
	protected $casts = [
		'porcentaje' => 'int'
	];

	protected $fillable = [
		'nombre',
		'porcentaje'
	];

	public function comisiones()
	{
		return $this->hasMany(\App\Comisione::class, 'socios_id');
	}
}
