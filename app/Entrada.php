<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 28 Jun 2019 17:50:51 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Entrada
 * 
 * @property int $id
 * @property int $charters_id
 * @property int $registrado_por
 * @property \Carbon\Carbon $fecha
 * @property float $monto
 * @property string $comentario
 * @property string $banco
 * @property string $referencia
 * @property string $tipo_recibo
 * @property string $link_papeleta_pago
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Charter $charter
 * @property \App\User $user
 *
 * @package App
 */
class Entrada extends Eloquent
{
	protected $casts = [
		'charters_id' => 'int',
		'registrado_por' => 'int',
		'monto' => 'float'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'charters_id',
		'registrado_por',
		'fecha',
		'monto',
		'comentario',
		'banco',
		'referencia',
		'tipo_recibo',
		'link_papeleta_pago'
	];

	public function charter()
	{
		return $this->belongsTo(\App\Charter::class, 'charters_id');
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'registrado_por');
	}
}
