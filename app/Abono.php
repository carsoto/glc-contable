<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 27 Jun 2019 19:50:38 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Abono
 * 
 * @property int $id
 * @property int $users_id
 * @property string $tipo
 * @property float $monto
 * @property \Carbon\Carbon $fecha
 * @property string $comentario
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $charters_id
 * 
 * @property \App\Charter $charter
 * @property \App\User $user
 *
 * @package App
 */
class Abono extends Eloquent
{
	protected $casts = [
		'users_id' => 'int',
		'monto' => 'float',
		'charters_id' => 'int'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'users_id',
		'tipo',
		'monto',
		'fecha',
		'comentario',
		'charters_id'
	];

	public function charter()
	{
		return $this->belongsTo(\App\Charter::class, 'charters_id');
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'users_id');
	}
}
