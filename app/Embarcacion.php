<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 30 Aug 2019 21:47:27 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Embarcacion
 * 
 * @property int $id
 * @property string $imo
 * @property string $nombre
 * @property string $anyo_construccion
 * @property string $refit
 * @property int $puerto_registro_id
 * @property int $companias_embarcacion_id
 * @property int $modelos_embarcacion_id
 * @property int $tipos_patente_id
 * @property string $capacidad
 * @property string $eslora
 * @property string $manga
 * @property string $puntal
 * @property string $velocidad_crucero
 * @property string $nro_tripulantes
 * @property string $estabilizadores
 * @property string $internet
 * @property int $kayacks
 * @property int $paddle_boards
 * @property string $ameneties
 * @property string $trajes_neopreno
 * @property string $equipo_snorkel
 * @property int $porcentaje_comision
 * @property string $politicas_pago
 * @property string $cancelaciones
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\CompaniasEmbarcacion $companias_embarcacion
 * @property \App\ModelosEmbarcacion $modelos_embarcacion
 * @property \App\Puerto $puerto
 * @property \App\TiposPatente $tipos_patente
 * @property \Illuminate\Database\Eloquent\Collection $charters
 * @property \Illuminate\Database\Eloquent\Collection $itinerarios
 * @property \Illuminate\Database\Eloquent\Collection $imagenes_embarcacions
 *
 * @package App
 */
class Embarcacion extends Eloquent
{
	protected $table = 'embarcacion';

	protected $casts = [
		'puerto_registro_id' => 'int',
		'companias_embarcacion_id' => 'int',
		'modelos_embarcacion_id' => 'int',
		'tipos_patente_id' => 'int',
		'kayacks' => 'int',
		'paddle_boards' => 'int',
		'porcentaje_comision' => 'int'
	];

	protected $fillable = [
		'imo',
		'nombre',
		'anyo_construccion',
		'refit',
		'puerto_registro_id',
		'companias_embarcacion_id',
		'modelos_embarcacion_id',
		'tipos_patente_id',
		'capacidad',
		'eslora',
		'manga',
		'puntal',
		'velocidad_crucero',
		'nro_tripulantes',
		'estabilizadores',
		'internet',
		'kayacks',
		'paddle_boards',
		'ameneties',
		'trajes_neopreno',
		'equipo_snorkel',
		'porcentaje_comision',
		'politicas_pago',
		'cancelaciones'
	];

	public function companias_embarcacion()
	{
		return $this->belongsTo(\App\CompaniasEmbarcacion::class);
	}

	public function modelos_embarcacion()
	{
		return $this->belongsTo(\App\ModelosEmbarcacion::class);
	}

	public function puerto()
	{
		return $this->belongsTo(\App\Puerto::class, 'puerto_registro_id');
	}

	public function tipos_patente()
	{
		return $this->belongsTo(\App\TiposPatente::class);
	}

	public function charters()
	{
		return $this->belongsToMany(\App\Charter::class, 'charters_embarcacion', 'embarcacion_id', 'charters_id')
					->withPivot('id', 'itinerarios_id')
					->withTimestamps();
	}

	public function itinerarios()
	{
		return $this->belongsToMany(\App\Itinerario::class, 'embarcacion_itinerarios', 'embarcacion_id', 'itinerarios_id')
					->withPivot('id', 'islas_puntos_visita_id', 'orden', 'dias_id', 'hora');
	}

	public function imagenes_embarcacions()
	{
		return $this->hasMany(\App\ImagenesEmbarcacion::class);
	}
}
