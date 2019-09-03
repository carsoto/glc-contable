<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'Auth\LoginController@showLoginForm');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
	Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

	/********************************************************* CHARTERS *****************************************************************************************************/
	Route::group(['middleware' => 'auth', 'prefix' => 'charters'], function () {
		Route::get('/index', 'CharterController@index')->name('admin.charters.index');
		Route::get('/create', 'CharterController@create')->name('admin.charters.create');
		Route::get('/charters-dashboard', 'CharterController@dashboard')->name('admin.charters.dashboard');
		Route::get('/charters-eliminados', 'CharterController@historial_charters')->name('admin.historial-charters-eliminados');
		Route::get('/variantes-patente/{id_patente}', 'CharterController@variantes_patente')->name('admin.variantes-patente');
		Route::get('/embarcaciones/{patente}', 'EmbarcacionController@embarcaciones')->name('admin.embarcaciones.patente');
		Route::get('/embarcaciones/info/{id_embarcacion}', 'EmbarcacionController@embarcaciones_informacion')->name('admin.embarcaciones.info');
		Route::post('/crear-charter', 'CharterController@store')->name('admin.crear-charter');
		Route::get('/eliminar-charter/{charter_id}', 'CharterController@destroy')->name('admin.eliminar-charter');
		Route::get('/opciones-charter/{charter_id}', 'CharterController@opciones')->name('admin.opciones-charter');
		Route::get('/editar-charter/{charter_id}', 'CharterController@editar')->name('admin.editar-charter');
		Route::post('/actualizar-charter', 'CharterController@update')->name('admin.actualizar-info-charter');
	});

	/********************************************************* VENTAS *****************************************************************************************************/
	Route::group(['middleware' => 'auth', 'prefix' => 'ventas'], function () {
		//Charter
		Route::post('/crear-charter', 'CharterController@store')->name('admin.comisiones-crear-charter');
		Route::get('/actualizar-charter/{charter_id}', 'CharterController@edit')->name('admin.comisiones-actualizar-charter');
		Route::get('/eliminar-charter/{charter_id}', 'CharterController@destroy')->name('admin.comisiones-eliminar-charter');
		Route::get('/charters-eliminados', 'CharterController@historial_charters')->name('admin.historial-charters-eliminados');

		Route::get('/comisiones-charters', 'ContabilidadController@index')->name('admin.comisiones-charters');

		Route::get('/editar-charter/{charter_id}', 'ContabilidadController@edit')->name('admin.comisiones-editar-charter');
		Route::get('/comisiones/charters', 'ContabilidadController@comisiones_charters')->name('admin.comisiones.charters');
		Route::get('/exportar-pdf/{charter_id}', 'ContabilidadController@exportarPDF')->name('admin.comisiones.charters.exportarPDF');
		
		//Sección Entradas
		Route::post('/editar-charter/crear-entrada-charter', 'ContabilidadController@crear_entrada_charter')->name('admin.crear-entrada-charter');
		Route::get('/editar-charter/historial-entradas/{id_charter}', 'ContabilidadController@historial_entradas')->name('admin.historial.entradas');
		Route::get('/editar-charter/editar-entrada/{id_entrada}', 'ContabilidadController@edit_entrada')->name('admin.editar.entrada');
		Route::post('/editar-charter/actualizar-entrada-charter', 'ContabilidadController@actualizar_entrada_charter')->name('admin.actualizar-entrada-charter');
		Route::get('/editar-charter/historial/entradas', 'ContabilidadController@historial_acciones')->name('admin.historial-entradas');
		Route::get('/editar-charter/eliminar-entrada/{entrada_id}', 'ContabilidadController@eliminar_entrada')->name('admin.eliminar-entrada');

		//Sección Comisiones
		Route::post('/editar-charter/crear-abono-comision', 'ContabilidadController@crear_abono_comision')->name('admin.crear-abono-comision');
		Route::get('/editar-charter/historial-abonos-comisiones/{id_comision}', 'ContabilidadController@abonos_comisiones')->name('admin.historial.abonos.comisiones');
		Route::get('/editar-charter/eliminar-abono/{comision_id}/{abono_id}', 'ContabilidadController@eliminar_abono_comision')->name('admin.eliminar.abono');
		Route::get('/editar-charter/historial/comisiones', 'ContabilidadController@historial_acciones')->name('admin.historial-comisiones');

		//Sección Salidas
		Route::post('/editar-charter/crear-gasto', 'ContabilidadController@crear_gasto')->name('admin.crear-gasto');
		Route::get('/editar-charter/historial/gastos/{tipo}/{id_charter}', 'ContabilidadController@historial_gastos')->name('admin.historial.gastos');
		Route::get('/editar-charter/gastos/{tipo}/{id_charter}', 'ContabilidadController@gastos')->name('admin.gastos');
		Route::get('/editar-charter/eliminar-gasto/{gasto_id}', 'ContabilidadController@eliminar_gasto')->name('admin.eliminar.gasto');
		Route::get('/editar-charter/editar-gasto/{gasto_id}', 'ContabilidadController@edit_gasto')->name('admin.editar.gasto');
		Route::post('/editar-charter/actualizar-gasto', 'ContabilidadController@actualizar_gasto')->name('admin.actualizar.gasto');
	});

	/********************************************************* BALANCE SOCIOS ********************************************************************************************/
	Route::group(['middleware' => 'auth', 'prefix' => 'balance-socios'], function () {
		Route::get('/', 'ContabilidadController@balance_socios')->name('admin.balance-socios');
		Route::get('/empleados', 'ContabilidadController@balance_empleados')->name('admin.balance-empleados');
	});

	/********************************************************* PEDIDOS ********************************************************************************************/
	Route::group(['middleware' => 'auth', 'prefix' => 'pedidos'], function () {
		Route::get('/', 'PedidosController@index')->name('admin.pedidos');
		Route::get('/dashboard', 'PedidosController@dashboard')->name('admin.dashboard.pedidos');
		Route::get('/eliminados', 'PedidosController@historial_pedidos')->name('admin.historial-pedidos-eliminados');
		Route::get('/eliminar-pedido/{pedido_id}', 'PedidosController@destroy')->name('admin.eliminar-pedido');
		Route::post('/registrar-pedido', 'PedidosController@store')->name('admin.registrar-pedido');
		Route::get('/editar-pedido/{id_pedido}', 'PedidosController@edit')->name('admin.editar-pedido');
		Route::post('/actualizar-pedido', 'PedidosController@update')->name('admin.actualizar-pedido');
		Route::get('/seguimientos/{pedido_id}', 'PedidosController@seguimientos')->name('admin.seguimientos');
		Route::post('/registrar-seguimiento', 'PedidosController@registrar_seguimiento')->name('admin.registrar-seguimiento');
		Route::get('/editar-seguimiento/{seguimiento_id}', 'PedidosController@editar_seguimiento')->name('admin.editar-seguimiento');
		Route::post('/actualizar-seguimiento', 'PedidosController@actualizar_seguimiento')->name('admin.actualizar-seguimiento');
	});
});