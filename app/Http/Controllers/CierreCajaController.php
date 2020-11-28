<?php

namespace App\Http\Controllers;

use App\Models\Cambio;
use App\Models\CorresponsalDiario;
use App\Models\Egreso;
use App\Models\Giro;
use App\Models\Moneda;
use App\Models\Servicio;
use App\Models\Transferencia;
use App\Models\Traslado;
use App\Models\TrasladoCaja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CierreCajaController extends Controller
{
    public $id = 1234567891;
    public $result = [];

    public function  getInfo()
    {

        // $this->id = request()->get("id");

        $Monedas = $this->getMonedas();

        $resultado = collect();
        foreach ($Monedas as $moneda) {

            $data["Nombre"] = $moneda->Nombre;
            $data["Codigo"] = $moneda->Codigo;
            $data["Id"] = $moneda->Id_Moneda;
            $movimientos = [];
            //$movimientos["Nombre"] 
            $movimientos[] = $this->ConsultarIngresosEgresosCambios($moneda->Id_Moneda);
            $movimientos[] =  $this->ConsultarIngresosEgresosTransferencias($moneda->Id_Moneda);
            $movimientos[]   = $this->ConsultarIngresosEgresosGiros($moneda->Id_Moneda);
            $movimientos[] = $this->ConsultarIngresosEgresosTraslados($moneda->Id_Moneda);
            $movimientos[]  = $this->ConsultarIngresosEgresosCorresponsal($moneda->Id_Moneda);
            $movimientos[] = $this->ConsultarIngresosEgresosServicios($moneda->Id_Moneda);

            $data["Movimientos"] = $movimientos;
            $resultado->push($data);
        }

        return $resultado;
    }


    public function ConsultarIngresosEgresosCambios($Id_Moneda)
    {
        // $cambiosArray = [];
        // $cambiosArray['mov'] = [];

        // foreach ($this->getMonedas() as $moneda) {
        $cambios =   Cambio::where('Moneda_Origen', $Id_Moneda)
            // ->with('moneda', function ($q) {
            //     $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
            // })
            // ->where('Fecha', $this->getFecha())
            ->where('Identificacion_Funcionario', $this->id)
            ->where('Estado', '<>', 'Anulado')
            ->select(
                DB::raw('IF(sum(Valor_Origen) > 0, sum(Valor_Origen), 0) AS Ingreso_Total , "Cambios" as Nombre '),
                DB::raw('IF(sum(Valor_Destino) > 0, sum(Valor_Destino), 0) AS Egreso_Total')
            )
            ->groupByRaw('Moneda_Origen')
            ->first();

        // array_push($cambiosArray['mov'],  $cambios);
        // }

        if (!$cambios) {
              return ['Ingreso_Total' => '0', 'Nombre' => 'Cambios', 'Egreso_Total' => '0'];
        }
        return  $cambios;
    }


    public function ConsultarIngresosEgresosTransferencias($Id_Moneda)
    {
        // $transferenciasArray = [];
        // $transferenciasArray['mov'] = [];
        // foreach ($this->getMonedas() as $moneda) {
        $transferencias =   Transferencia::where('Moneda_Origen', $Id_Moneda)
            // ->with('moneda', function ($q) {
            //     $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
            // })
            // ->where('Fecha', $this->getFecha())
            ->where('Identificacion_Funcionario', $this->id)
            ->where('Estado',  'Activa')
            ->orWhere('Estado',  'Pagada')
            ->select(
                DB::raw('IF(sum(Cantidad_Recibida) > 0, sum(Cantidad_Recibida), 0) AS Ingreso_Total, "Transferencias" as Nombre'),
                DB::raw('0 AS Egreso_Total')
            )
            ->groupByRaw('Moneda_Origen')
            ->first();

        // array_push($transferenciasArray['mov'],  $transferencias);
        // }
        // $transferenciasArray['Modulo'] = 'Transferencias';
        if (!$transferencias) {
            return ['Ingreso_Total' => '0', 'Nombre' => 'Transferencias', 'Egreso_Total' => '0'];
      }
        return  $transferencias;
    }


    public function ConsultarIngresosEgresosGiros($Id_Moneda)
    {
        // $girosArray = [];
        // $girosArray['mov'] = [];

        // foreach ($this->getMonedas() as $moneda) {
        $giros =   Giro::where('Id_Moneda', $Id_Moneda)
            ->with('moneda', function ($q) {
                $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
            })
            // ->where('Fecha', $this->getFecha())
            ->where('Identificacion_Funcionario', $this->id)
            ->where('Estado', '<>', 'Anulado')
            ->select(
                DB::raw('IF(sum(Valor_Total) > 0, sum(Valor_Total), 0) AS Ingreso_Total, "Giros" as Nombre'),
                DB::raw('IF(sum(Valor_Entrega) > 0, sum(Valor_Entrega), 0) AS Egreso_Total')
            )
            ->groupByRaw('Id_Moneda')
            ->first();

        // array_push($girosArray['mov'],  $giros);
        // }
        // $girosArray['Modulo'] = 'Giros';
        if (!$giros) {
            return ['Ingreso_Total' => '0', 'Nombre' => 'Giros', 'Egreso_Total' => '0'];
      }
        return  $giros;
    }
    public function ConsultarIngresosEgresosTraslados($Id_Moneda)
    {
        // $trasladosArray = [];
        // $trasladosArray['mov'] = [];
        // foreach ($this->getMonedas() as $moneda) {
        $traslados =   TrasladoCaja::where('Id_Moneda', $Id_Moneda)
            ->with('moneda', function ($q) {
                $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
            })
            // ->where('Fecha', $this->getFecha())
            ->when('Funcionario_Destino',  function ($q) {
                $q->where('Funcionario_Destino', $this->id)
                    ->select(DB::raw('IF(sum(Valor) > 0, sum(Valor_Total), 0) AS Ingreso_Total, "Traslados" as Nombre'))
                    ->groupByRaw('Id_Moneda');
            })
            ->when('Id_Cajero_Origen',  function ($q) {
                $q->where('Funcionario_Destino', $this->id)
                    ->select(DB::raw('IF(sum(Valor) > 0, sum(Valor), 0) AS Engreso_Total,  "Traslados" as Nombre'))
                    ->groupByRaw('Id_Moneda');
            })
            ->where('Estado', 'Aprobado')
            ->first();

        // array_push($trasladosArray['mov'],  $traslados);
        // }
        // $trasladosArray['Modulo'] = 'Traslados';
        if (!$traslados) {
            return ['Ingreso_Total' => '0', 'Nombre' => 'Traslados', 'Egreso_Total' => '0'];
      }
        return $traslados;
    }

    public function ConsultarIngresosEgresosCorresponsal($Id_Moneda)
    {
        // $coresponsalesArray = [];
        // $coresponsalesArray['mov'] = [];
        // foreach ($this->getMonedas() as $moneda) {
        $corresponsales =   CorresponsalDiario::where('Id_Moneda', $Id_Moneda)
            ->with('moneda', function ($q) {
                $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
            })
            // ->where('Fecha', $this->getFecha())
            ->where('Identificacion_Funcionario', $this->id)
            ->select(
                DB::raw('IF(sum(Consignacion) > 0, sum(Consignacion), 0) AS Ingreso_Total,  "Corresponsal" as Nombre'),
                DB::raw('IF(sum(Retiro) > 0, sum(Retiro), 0) AS Engreso_Total')
            )
            ->groupByRaw('Id_Moneda')
            ->first();

        // array_push($coresponsalesArray['mov'],  $corresponsales);
        // }
        // $coresponsalesArray['Modulo'] = 'Corresponsales';
        
        if (!$corresponsales) {
            return ['Ingreso_Total' => '0', 'Nombre' => 'Corresponsal', 'Egreso_Total' => '0'];
      }
        return $corresponsales;
    }

    public function ConsultarIngresosEgresosServicios($Id_Moneda)
    {
        // $serviciosArray = [];
        // $serviciosArray['mov'] = [];
        // foreach ($this->getMonedas() as $moneda) {
        $servicios =   Servicio::where('Id_Moneda', $Id_Moneda)
            ->with('moneda', function ($q) {
                $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
            })
            // ->where('Fecha', $this->getFecha())
            ->where('Identificacion_Funcionario', $this->id)
            ->select(
                DB::raw('IF(sum(Valor + Comision) > 0, sum(Valor + Comision), 0) AS Ingreso_Total, "Servicios" as Tipo '),
                DB::raw('0 AS Egreso_Total')
            )
            ->groupByRaw('Id_Moneda')
            ->first();

        // array_push($serviciosArray['mov'],  $servicios);
        // }
        // $serviciosArray['Modulo'] = 'Servicios';

        if (!$servicios) {
            return ['Ingreso_Total' => '0', 'Nombre' => 'Servicios', 'Egreso_Total' => '0'];
      }
        return  $servicios;
    }

    public function getMonedas()
    {
        return Moneda::all();
    }
    public function getFecha()
    {
        return Carbon::now();
    }
}
