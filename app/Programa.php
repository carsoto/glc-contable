<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 30 Aug 2019 21:47:27 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Programa
 * 
 * @property int $id
 * @property string $desc_programa
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $charters
 *
 * @package App
 */
class Programa extends Eloquent
{
	protected $table = 'programa';

	protected $fillable = [
		'desc_programa'
	];

	public function charters()
	{
		return $this->hasMany(\App\Charter::class);
	}
}
