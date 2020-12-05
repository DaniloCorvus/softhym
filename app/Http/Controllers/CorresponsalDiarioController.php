<?php

namespace App\Http\Controllers;

use App\Models\CorresponsalDiario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CorresponsalDiarioController extends Controller
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
     * @param  \App\Models\CorresponsalDiario  $corresponsalDiario
     * @return \Illuminate\Http\Response
     */
    public function show(CorresponsalDiario $corresponsalDiario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CorresponsalDiario  $corresponsalDiario
     * @return \Illuminate\Http\Response
     */
    public function edit(CorresponsalDiario $corresponsalDiario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CorresponsalDiario  $corresponsalDiario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $corresponsal = CorresponsalDiario::where('Id_Corresponsal_Diario', request()->get('Id_Corresponsal_Diario'))->first();
            $corresponsal->update(request()->all());
            return response()->json(['codigo' => 'success', 'titulo' => 'Exito', 'mensaje' => 'Update correcto']);
        } catch (\Throwable $th) {
            return response()->json(['codigo' => 'warning', 'titulo' => 'Alerta', 'mensaje' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CorresponsalDiario  $corresponsalDiario
     * @return \Illuminate\Http\Response
     */
    public function destroy(CorresponsalDiario $corresponsalDiario)
    {
        //
    }
}
