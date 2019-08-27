<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Aug 2019 21:05:15 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Paise
 * 
 * @property int $id
 * @property string $codigo_alpha_2
 * @property string $codigo_alpha_3
 * @property string $pais
 * @property string $nacionalidad
 *
 * @package App
 */
class Paise extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'codigo_alpha_2',
		'codigo_alpha_3',
		'pais',
		'nacionalidad'
	];
}
