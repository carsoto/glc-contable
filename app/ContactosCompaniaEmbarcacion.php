<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 27 Aug 2019 21:28:47 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ContactosCompaniaEmbarcacion
 * 
 * @property int $id
 * @property int $compania_embarcacion_id
 * @property string $nombre
 * @property string $email
 * @property string $telefono
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\CompaniasEmbarcacion $companias_embarcacion
 *
 * @package App
 */
class ContactosCompaniaEmbarcacion extends Eloquent
{
	protected $table = 'contactos_compania_embarcacion';

	protected $casts = [
		'compania_embarcacion_id' => 'int'
	];

	protected $fillable = [
		'compania_embarcacion_id',
		'nombre',
		'email',
		'telefono'
	];

	public function companias_embarcacion()
	{
		return $this->belongsTo(\App\CompaniasEmbarcacion::class, 'compania_embarcacion_id');
	}
}
