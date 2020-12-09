<?php

namespace App\Http\Controllers;

use App\Models\Cambio;
use App\Models\CorresponsalDiario;
use App\Models\Giro;
use App\Models\Moneda;
use App\Models\Servicio;
use App\Models\Transferencia;
use App\Models\TrasladoCaja;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CierreCajaController extends Controller
{
    public $id = 1524854;
    public $result = [];

    public function  getInfo()
    {
        // return response()->json($this->getMonedas());

        // $this->id = request()->get("id");

        $Monedas = $this->getMonedas();
        $resultado = collect();


        foreach ($Monedas as $moneda) {

            $data["Nombre"] = $moneda->Nombre;
            $data["Codigo"] = $moneda->Codigo;
            $data["Id"] = $moneda->Id_Moneda;
            $movimientos = [];

            $movimientos[] = $this->ConsultarIngresosEgresosCambios($moneda->Id_Moneda);
            $movimientos[] = $this->ConsultarIngresosEgresosTransferencias($moneda->Id_Moneda);
            $movimientos[] = $this->ConsultarIngresosEgresosGiros($moneda->Id_Moneda);
            $movimientos[] = $this->ConsultarIngresosEgresosTraslados($moneda->Id_Moneda);
            $movimientos[] = $this->ConsultarIngresosEgresosCorresponsal($moneda->Id_Moneda);
            $movimientos[] = $this->ConsultarIngresosEgresosServicios($moneda->Id_Moneda);

            $data["Movimientos"] = $movimientos;
            $resultado->push($data);
        }

        return response()->json($resultado);
    }


    public function ConsultarIngresosEgresosCambios($Id_Moneda)
    {
        $cambios =   Cambio::where('Moneda_Origen', $Id_Moneda)

            // ->where('Fecha', $this->getFecha())
            ->where('Identificacion_Funcionario', $this->id)
            ->where('Estado', '<>', 'Anulado')
            ->select(
                DB::raw('IF(sum(Valor_Origen) > 0, sum(Valor_Origen), 0) AS Ingreso_Total , "Cambios" as Nombre '),
                DB::raw('IF(sum(Valor_Destino) > 0, sum(Valor_Destino), 0) AS Egreso_Total')
            )
            ->groupByRaw('Moneda_Origen')
            ->first();

        return (!$cambios) ? ['Ingreso_Total' => '0', 'Nombre' => 'Cambios', 'Egreso_Total' => '0'] : $cambios;
    }


    public function ConsultarIngresosEgresosTransferencias($Id_Moneda)
    {

        $transferencias =   Transferencia::where('Moneda_Origen', $Id_Moneda)

            // ->where('Fecha', $this->getFecha())
            ->orWhere('Estado',  'Pagada')
            ->where('Identificacion_Funcionario', $this->id)
            ->where('Estado',  'Activa')
            ->select(
                DB::raw('IF(sum(Cantidad_Recibida) > 0, sum(Cantidad_Recibida), 0) AS Ingreso_Total, "Transferencias" as Nombre'),
                DB::raw('0 AS Egreso_Total')
            )
            ->groupByRaw('Moneda_Origen')
            ->first();


        return (!$transferencias) ? ['Ingreso_Total' => '0', 'Nombre' => 'Transferencias', 'Egreso_Total' => '0'] : $transferencias;
    }


    public function ConsultarIngresosEgresosGiros($Id_Moneda)
    {

        $giros =   Giro::where('Id_Moneda', $Id_Moneda)

            // ->where('Fecha', $this->getFecha())
            ->where('Identificacion_Funcionario', $this->id)
            ->where('Estado', '<>', 'Anulado')
            ->select(
                DB::raw('IF(sum(Valor_Total) > 0, sum(Valor_Total), 0) AS Ingreso_Total, "Giros" as Nombre'),
                DB::raw('IF(sum(Valor_Entrega) > 0, sum(Valor_Entrega), 0) AS Egreso_Total')
            )
            ->groupByRaw('Id_Moneda')
            ->first();

        return (!$giros) ? ['Ingreso_Total' => '0', 'Nombre' => 'Giros', 'Egreso_Total' => '0'] :  $giros;
    }
    public function ConsultarIngresosEgresosTraslados($Id_Moneda)
    {

        $traslados =   TrasladoCaja::where('Id_Moneda', $Id_Moneda)

            // ->where('Fecha', $this->getFecha())
            ->when('Funcionario_Destino',  function ($q) {
                $q->where('Funcionario_Destino', $this->id)
                    ->select(DB::raw('IF(sum(Valor) > 0, sum(Valor_Total), 0) AS Ingreso_Total, "Traslados" as Nombre'))
                    ->groupByRaw('Id_Moneda');
            })
            ->when('Id_Cajero_Origen',  function ($q) {
                $q->where('Funcionario_Destino', $this->id)
                    ->select(DB::raw('IF(sum(Valor) > 0, sum(Valor), 0) AS Egreso_Total,  "Traslados" as Nombre'))
                    ->groupByRaw('Id_Moneda');
            })
            ->where('Estado', 'Aprobado')
            ->first();


        return (!$traslados) ? ['Ingreso_Total' => '0', 'Nombre' => 'Traslados', 'Egreso_Total' => '0'] : $traslados;
    }

    public function ConsultarIngresosEgresosCorresponsal($Id_Moneda)
    {

        $corresponsales =   CorresponsalDiario::where('Id_Moneda', $Id_Moneda)

            // ->where('Fecha', $this->getFecha())
            ->where('Identificacion_Funcionario', $this->id)
            ->select(
                DB::raw('IF(sum(Consignacion) > 0, sum(Consignacion), 0) AS Ingreso_Total,  "Corresponsal" as Nombre'),
                DB::raw('IF(sum(Retiro) > 0, sum(Retiro), 0) AS Egreso_Total')
            )
            ->groupByRaw('Id_Moneda')
            ->first();

        return (!$corresponsales) ?  ['Ingreso_Total' => '0', 'Nombre' => 'Corresponsal', 'Egreso_Total' => '0'] : $corresponsales;
    }

    public function ConsultarIngresosEgresosServicios($Id_Moneda)
    {
        $servicios =   Servicio::where('Id_Moneda', $Id_Moneda)

            // ->where('Fecha', $this->getFecha())
            ->where('Identificacion_Funcionario', $this->id)
            ->select(
                DB::raw('IF(sum(Valor + Comision) > 0, sum(Valor + Comision), 0) AS Ingreso_Total, "Servicios" as Nombre '),
                DB::raw('0 AS Egreso_Total')
            )
            ->groupByRaw('Id_Moneda')
            ->first();

        return (!$servicios) ? ['Ingreso_Total' => '0', 'Nombre' => 'Servicios', 'Egreso_Total' => '0'] :  $servicios;
    }

    public function getMonedas()
    {
        return Moneda::where('Estado', 'Activa')->get();
    }
    public function getFecha()
    {
        return Carbon::now();
    }
}
