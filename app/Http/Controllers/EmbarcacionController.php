<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TiposPatente;
use App\Embarcacion;
use Response;

class EmbarcacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function embarcaciones($patente){
        $patente = TiposPatente::where('descripcion', '=', $patente)->first();
        $embarcaciones = Embarcacion::where('tipos_patente_id', '=', $patente->id)->get();

        return Response::json(array('embarcaciones' => $embarcaciones));
    }

    public function embarcaciones_informacion($id_embarcacion){
        $embarcacion = Embarcacion::find($id_embarcacion);
        $itinerarios = array();
        
        foreach ($embarcacion->itinerarios as $key => $itinerario) {
            $itinerarios[$itinerario->id] = $itinerario->nombre;
        }

        return Response::json(array('embarcacion' => $embarcacion, 'itinerarios' => $itinerarios));
    }
}
