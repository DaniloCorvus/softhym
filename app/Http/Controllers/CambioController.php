<?php

namespace App\Http\Controllers;

use App\Models\Cambio;
use App\Models\Devolucioncambio;
use App\Models\Logsistema;
use App\Traits\GenerateCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Barryvdh\DomPDF\Facade as PDF;
use finfo;

class CambioController extends Controller
{

    use GenerateCode;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

//     SELECT
//     Id_Cambio,
//     Codigo,
//     Fecha,
//     Id_Caja,
//     Id_Oficina,
//     (IFNULL( (SELECT Nombre FROM Moneda WHERE Id_Moneda = Moneda_Origen AND Estado = 'Activa'), 'Moneda Inexistente')) AS Moneda_Origen,
//     (IFNULL( (SELECT Nombre FROM Moneda WHERE Id_Moneda = Moneda_Destino AND Estado = 'Activa'), 'Moneda Inexistente')) AS Moneda_Destino,
//     (IFNULL( (SELECT Codigo FROM Moneda WHERE Id_Moneda = Moneda_Origen AND Estado = 'Activa'), 'Codigo Inexistente')) AS Codigo_Moneda_Origen,
//     (IFNULL( (SELECT Codigo FROM Moneda WHERE Id_Moneda = Moneda_Destino AND Estado = 'Activa'), 'Codigo Inexistente')) AS Codigo_Moneda_Destino,
//     Tasa,
//     (Valor_Destino - (SELECT SUM(valor_recibido) FROM devolucioncambios WHERE cambio_id =	34))  As Valor_Destino,
//     Valor_Origen,
//     TotalPago,
//     Vueltos,
//     Recibido,
//     Tipo,
    
//     T.Nombre,
//     T.Id_Tercero
    
// FROM Cambio c
// LEFT JOIN Tercero AS T ON c.Tercero_id = T.Id_Tercero
// WHERE Id_Cambio = 34

    public function index()
    {
        try {
            return  response()->json(['codigo' => 'success', 'query_data' => Cambio::whereDate('Fecha', Carbon::today())->where('Identificacion_Funcionario', request()->get('funcionario'))->get()]);
        } catch (\Throwable $th) {
            throw $th;
        }
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


            DB::beginTransaction();
            $code =  $this->generateCod(request()->get('modulo'));
            $datos = json_decode(request()->get('datos'));
            $tercero =  (isset($datos->Id_Tercero)) ? $datos->Id_Tercero : null;

            Cambio::create([
                'Tipo' => $datos->Tipo,
                'Codigo' =>  $code,
                'Fecha' => Carbon::now(),
                'Id_Caja' => $datos->Id_Caja,
                'Id_Oficina' => $datos->Id_Oficina,
                'Moneda_Origen' => $datos->Moneda_Origen,
                'Moneda_Destino' => $datos->Moneda_Destino,
                'Tasa' => $datos->Tasa,
                'Valor_Origen' => ($datos->Tipo == 'Venta') ? $datos->Valor_Destino :  $datos->Valor_Origen,
                'Valor_Destino' => ($datos->Tipo == 'Venta') ?  $datos->Valor_Origen :  $datos->Valor_Destino,
                'TotalPago' => ($datos->TotalPago == '') ? 0 : $datos->TotalPago,
                'Vueltos' => $datos->Vueltos,
                'Recibido' => $datos->Recibido,
                'Estado' => $datos->Estado,
                'Identificacion_Funcionario' => $datos->Identificacion_Funcionario,
                'Tercero_id' => ($tercero == null) ? null : $tercero->Id_Tercero,
                'fomapago_id' =>  $datos->fomapago,
            ]);

            Logsistema::create([
                'Id_Funcionario' => $datos->Identificacion_Funcionario,
                'Accion' => request()->get('modulo'),
                'Detalle' => "Nuevo cambio de divisas de tipo: $datos->Tipo !",
                'Fecha_Registro' => Carbon::now(),
            ]);
            DB::commit();
            return  response()->json('ok');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cambio  $cambio
     * @return \Illuminate\Http\Response
     */
    public function show(Cambio $cambio)
    {
        //    En  original backend
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cambio  $cambio
     * @return \Illuminate\Http\Response
     */
    public function edit(Cambio $cambio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cambio  $cambio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cambio $cambio)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cambio  $cambio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cambio $cambio)
    {
        //
    }

    public function printCambio()
    {
        $pdf = PDF::loadView('pdfs.invoice');
        return $pdf->stream('invoice.pdf');
    }
}
