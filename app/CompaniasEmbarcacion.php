<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Aug 2019 21:05:15 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CompaniasEmbarcacion
 * 
 * @property int $id
 * @property string $razon_social
 * @property string $ruc
 * @property string $direccion
 * @property string $telefono_1
 * @property string $telefono_2
 * @property string $banco
 * @property string $cuenta_bancaria
 * @property string $acuerdo_comercial
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $contactos_compania_embarcacions
 * @property \Illuminate\Database\Eloquent\Collection $embarcacions
 *
 * @package App
 */
class CompaniasEmbarcacion extends Eloquent
{
	protected $table = 'companias_embarcacion';

	protected $fillable = [
		'razon_social',
		'ruc',
		'direccion',
		'telefono_1',
		'telefono_2',
		'banco',
		'cuenta_bancaria',
		'acuerdo_comercial'
	];

	public function contactos_compania_embarcacions()
	{
		return $this->hasMany(\App\ContactosCompaniaEmbarcacion::class, 'compania_embarcacion_id');
	}

	public function embarcacions()
	{
		return $this->hasMany(\App\Embarcacion::class);
	}
}
