<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 06 Sep 2019 16:59:06 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Configuracion
 * 
 * @property int $id
 * @property string $descripcion
 * @property int $cantd_dias_charter
 * @property string $valor
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App
 */
class Configuracion extends Eloquent
{
	protected $table = 'configuracion';

	protected $casts = [
		'cantd_dias_charter' => 'int'
	];

	protected $fillable = [
		'descripcion',
		'cantd_dias_charter',
		'valor'
	];
}
