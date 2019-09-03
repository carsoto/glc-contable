<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 30 Aug 2019 21:47:27 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ReglasNegocio
 * 
 * @property int $id
 * @property float $r_inicio
 * @property float $r_fin
 * @property float $monto
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $socios_regla_negocios
 *
 * @package App
 */
class ReglasNegocio extends Eloquent
{
	protected $table = 'reglas_negocio';

	protected $casts = [
		'r_inicio' => 'float',
		'r_fin' => 'float',
		'monto' => 'float'
	];

	protected $fillable = [
		'r_inicio',
		'r_fin',
		'monto'
	];

	public function socios_regla_negocios()
	{
		return $this->hasMany(\App\SociosReglaNegocio::class);
	}
}
