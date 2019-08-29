<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Servicio
 * 
 * @property int $id
 * @property string $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App
 */
class Servicio extends Eloquent
{
	protected $fillable = [
		'descripcion'
	];
}
