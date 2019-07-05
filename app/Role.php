<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 11 Apr 2019 05:59:40 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App
 */
class Role extends Eloquent
{
	protected $table = 'roles';

	protected $fillable = [
		'name',
		'description'
	];

	public function users()
	{
		return $this->hasMany(\App\User::class, 'roles_id');
	}
}
