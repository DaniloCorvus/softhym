<?php

namespace App\Http\Controllers;

use App\Models\Cambio;
use App\Models\Devolucioncambio;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\all;

class DevolucioncambioController extends Controller
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

        $data = json_decode(request()->get('data'));
        // return response()->json($data->Id_Cambio);
        $devolucion =     Devolucioncambio::create([
            'hora' => Carbon::now(),
            'cambio_id' => $data->Id_Cambio,
            'observacion' => ($data->Observacion) ? $data->Observacion : '',
            'motivodevolucioncambios_id' => $data->Motivo,
            'valor_recibido' => $data->Valor_Devolver,
            'valor_entregado' => $data->Valor_Devuelto,
        ]);

        return response()->json($devolucion);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Devolucioncambio  $devolucioncambio
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $cambio)
    {
        return response()->json(Cambio::find($cambio)->devolucioncambios()->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Devolucioncambio  $devolucioncambio
     * @return \Illuminate\Http\Response
     */
    public function edit(Devolucioncambio $devolucioncambio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Devolucioncambio  $devolucioncambio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Devolucioncambio $devolucioncambio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Devolucioncambio  $devolucioncambio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Devolucioncambio $devolucioncambio)
    {
        //
    }
}
