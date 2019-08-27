<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Aug 2019 21:05:15 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Banco
 * 
 * @property int $id
 * @property string $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App
 */
class Banco extends Eloquent
{
	protected $fillable = [
		'descripcion'
	];
}
