<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 06 Sep 2019 16:59:06 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Broker
 * 
 * @property int $id
 * @property string $nombre
 * @property string $email
 * @property string $empresa
 * @property string $telefono
 * @property int $porcentaje
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $charters
 *
 * @package App
 */
class Broker extends Eloquent
{
	protected $casts = [
		'porcentaje' => 'int'
	];

	protected $fillable = [
		'nombre',
		'email',
		'empresa',
		'telefono',
		'porcentaje'
	];

	public function charters()
	{
		return $this->hasMany(\App\Charter::class, 'brokers_id');
	}
}