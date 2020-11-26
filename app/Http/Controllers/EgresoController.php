<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use App\Models\Moneda;
use App\Models\MovimientoTercero;
use App\Models\Tercero;
use App\Traits\GenerateCode;
use App\Traits\getData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class EgresoController extends Controller
{
    use GenerateCode;
    use getData;
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
        try {


            $data = [];
            $datos = (array)json_decode(request()->get('modelo'));
            $datos['Fecha'] = Carbon::now();
            $datos['Codigo'] = $this->generateCod('Egreso');
            unset($datos['Id_Egreso'], $datos['Id_Grupo']);
            $data = $this->createFields($datos);
            $egreso = Egreso::create($data);

            $dataMovimiento['Fecha'] =  $data['Fecha'];
            $dataMovimiento['Valor']  =  $data['Valor'];
            $dataMovimiento['Id_Moneda_Valor'] = $data['Id_Moneda'];
            $dataMovimiento['Tipo'] = 'Egreso';
            $dataMovimiento['Id_Tercero'] = $data['Id_Tercero'];
            $dataMovimiento['Detalle'] = 'Egreso por ' . $data['Valor'] . ' ' . $this->GetCodigoMoneda($data['Id_Moneda']) . ' ' . ' con codigo ' . $data['Codigo'];
            $dataMovimiento['Id_Tipo_Movimiento'] = '6';
            $dataMovimiento['Valor_Tipo_Movimiento'] = $egreso->id;
            $dataMovimiento['Estado'] = 'Activo';
            $movimiento = MovimientoTercero::create($dataMovimiento);

            if ($datos['formaPago'] == 'credito') {
                $tercero = Tercero::find($data['Id_Tercero']);
                $tercero->Cupo =  (float)$tercero->Cupo - (float)$data['Valor'];
                $tercero->save();
            }

            return response()->json(['codigo' => 'success']);
        } catch (\Exception $th) {
            return response()->json(['codigo' => 'warning', 'Message' => $th->getMessage()]);
        }
    }

    function GetCodigoMoneda($id_moneda)
    {
        $codigo = Moneda::find($id_moneda, ['Nombre']);
        return $codigo->Nombre;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function show(Egreso $egreso)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function edit(Egreso $egreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Egreso $egreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Egreso $egreso)
    {
        //
    }
}
