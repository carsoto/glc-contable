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

/********************************************************* VENTAS *****************************************************************************************************/
	Route::get('/comisiones-charters', 'ComisionesController@index')->name('admin.comisiones-charters');
	Route::post('/crear-charter', 'ComisionesController@store')->name('admin.comisiones-crear-charter');
	Route::get('/editar-charter/{charter_id}', 'ComisionesController@edit')->name('admin.comisiones-editar-charter');
	Route::get('/actualizar-charter/{charter_id}', 'ComisionesController@actualizar')->name('admin.comisiones-actualizar-charter');
	Route::post('/actualizar-charter', 'ComisionesController@update')->name('admin.comisiones-actualizar-info-charter');
	Route::get('/comisiones/charters', 'ComisionesController@comisiones_charters')->name('admin.comisiones.charters');
	Route::get('/exportar-pdf/{charter_id}', 'ComisionesController@exportarPDF')->name('admin.comisiones.charters.exportarPDF');
	Route::get('/eliminar-charter/{charter_id}', 'ComisionesController@delete')->name('admin.comisiones-eliminar-charter');

	//Sección Entradas
	Route::post('/editar-charter/crear-entrada-charter', 'ComisionesController@crear_entrada_charter')->name('admin.crear-entrada-charter');
	Route::get('/editar-charter/historial-entradas/{id_charter}', 'ComisionesController@historial_entradas')->name('admin.historial.entradas');
	Route::get('/editar-charter/editar-entrada/{id_entrada}', 'ComisionesController@edit_entrada')->name('admin.editar.entrada');
	Route::post('/editar-charter/actualizar-entrada-charter', 'ComisionesController@actualizar_entrada_charter')->name('admin.actualizar-entrada-charter');

	//Sección Comisiones
	Route::post('/editar-charter/crear-abono-comision', 'ComisionesController@crear_abono_comision')->name('admin.crear-abono-comision');
	Route::get('/editar-charter/historial-abonos-comisiones/{id_comision}', 'ComisionesController@abonos_comisiones')->name('admin.historial.abonos.comisiones');

	//Sección Salidas
	Route::post('/editar-charter/crear-gasto', 'ComisionesController@crear_gasto')->name('admin.crear-gasto');
	Route::get('/editar-charter/historial-gastos/{id_gasto}', 'ComisionesController@historial_gastos')->name('admin.historial.gastos');
/*********************************************************************************************************************************************************************/	

/********************************************************* BALANCE SOCIOS ********************************************************************************************/
	Route::get('/balance-socios', 'ComisionesController@balance_socios')->name('admin.balance-socios');


/*********************************************************************************************************************************************************************/
});