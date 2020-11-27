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
    public $id = 1524854;
    public $result = [];

    public function  getInfo()
    {

        // $this->result = collect();

        // $this->id = request()->get("id");
        // $data = [];
        // $data['Modenas'] = [];
        $result['Modulos'] = [];

        $Monedas = array_values($this->getMonedas()->toArray());

        $Cambios = $this->ConsultarIngresosEgresosCambios();

        $Transferencias =  $this->ConsultarIngresosEgresosTransferencias();

        $Giros  = $this->ConsultarIngresosEgresosGiros();

        $Traslados = $this->ConsultarIngresosEgresosTraslados();

        $Corresponsales = $this->ConsultarIngresosEgresosCorresponsal();

        $Servicios  = $this->ConsultarIngresosEgresosServicios();

        $result['Monedas'] =  $Monedas;

        $result['Modulos'][] = $Cambios;
        $result['Modulos'][] = $Transferencias;
        $result['Modulos'][] = $Giros;
        $result['Modulos'][] = $Traslados;
        $result['Modulos'][] = $Corresponsales;
        $result['Modulos'][] = $Servicios;

        //  array_push($result['Modulos'], $Transferencias);
        // array_push($result['Modulos'], $Giros);
        // array_push($result['Modulos'], $Traslados);
        // array_push($result['Modulos'], $Corresponsales);
        // array_push($result['Modulos'], $Servicios);

        return $result;
    }


    public function ConsultarIngresosEgresosCambios()
    {
        $cambiosArray = [];
        $cambiosArray['mov'] = [];

        foreach ($this->getMonedas() as $moneda) {
            $cambios =   Cambio::where('Moneda_Origen', $moneda->Id_Moneda)
                ->with('moneda', function ($q) {
                    $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
                })
                // ->where('Fecha', $this->getFecha())
                ->where('Identificacion_Funcionario', $this->id)
                ->where('Estado', '<>', 'Anulado')
                ->select(
                    DB::raw('IF(sum(Valor_Origen) > 0, sum(Valor_Origen), 0) AS Ingreso_Total, Moneda_Origen , "Cambios" as Tipo '),
                    DB::raw('IF(sum(Valor_Destino) > 0, sum(Valor_Destino), 0) AS Egreso_Total')
                )
                ->groupByRaw('Moneda_Origen')
                ->first();

            array_push($cambiosArray['mov'],  $cambios);
        }
        $cambiosArray['Modulo'] = 'Cambios';
        return  $cambiosArray;
    }


    public function ConsultarIngresosEgresosTransferencias()
    {
        $transferenciasArray = [];
        $transferenciasArray['mov'] = [];
        foreach ($this->getMonedas() as $moneda) {
            $transferencias =   Transferencia::where('Moneda_Origen', $moneda->Id_Moneda)
                ->with('moneda', function ($q) {
                    $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
                })
                // ->where('Fecha', $this->getFecha())
                ->where('Identificacion_Funcionario', $this->id)
                ->where('Estado',  'Activa')
                ->orWhere('Estado',  'Pagada')
                ->select(
                    DB::raw('IF(sum(Cantidad_Recibida) > 0, sum(Cantidad_Recibida), 0) AS Ingreso_Total, Moneda_Origen'),
                    DB::raw('0 AS Egreso_Total')
                )
                ->groupByRaw('Moneda_Origen')
                ->first();

            array_push($transferenciasArray['mov'],  $transferencias);
        }
        $transferenciasArray['Modulo'] = 'Transferencias';
        return  $transferenciasArray;
    }


    public function ConsultarIngresosEgresosGiros()
    {
        $girosArray = [];
        $girosArray['mov'] = [];

        foreach ($this->getMonedas() as $moneda) {
            $giros =   Giro::where('Id_Moneda', $moneda->Id_Moneda)
                ->with('moneda', function ($q) {
                    $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
                })
                // ->where('Fecha', $this->getFecha())
                ->where('Identificacion_Funcionario', $this->id)
                ->where('Estado', '<>', 'Anulado')
                ->select(
                    DB::raw('IF(sum(Valor_Total) > 0, sum(Valor_Total), 0) AS Ingreso_Total, Id_Moneda'),
                    DB::raw('IF(sum(Valor_Entrega) > 0, sum(Valor_Entrega), 0) AS Engreso_Total')
                )
                ->groupByRaw('Id_Moneda')
                ->first();

            array_push($girosArray['mov'],  $giros);
        }
        $girosArray['Modulo'] = 'Giros';
        return  $girosArray;
    }
    public function ConsultarIngresosEgresosTraslados()
    {
        $trasladosArray = [];
        $trasladosArray['mov'] = [];
        foreach ($this->getMonedas() as $moneda) {
            $traslados =   TrasladoCaja::where('Id_Moneda', $moneda->Id_Moneda)
                ->with('moneda', function ($q) {
                    $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
                })
                // ->where('Fecha', $this->getFecha())
                ->when('Funcionario_Destino',  function ($q) {
                    $q->where('Funcionario_Destino', $this->id)
                        ->select(DB::raw('IF(sum(Valor) > 0, sum(Valor_Total), 0) AS Ingreso_Total'))
                        ->groupByRaw('Id_Moneda');
                })
                ->when('Id_Cajero_Origen',  function ($q) {
                    $q->where('Funcionario_Destino', $this->id)
                        ->select(DB::raw('IF(sum(Valor) > 0, sum(Valor), 0) AS Engreso_Total'))
                        ->groupByRaw('Id_Moneda');
                })
                ->where('Estado', 'Aprobado')
                ->first();

            array_push($trasladosArray['mov'],  $traslados);
        }
        $trasladosArray['Modulo'] = 'Traslados';
        return $trasladosArray;
    }

    public function ConsultarIngresosEgresosCorresponsal()
    {
        $coresponsalesArray = [];
        $coresponsalesArray['mov'] = [];
        foreach ($this->getMonedas() as $moneda) {
            $corresponsales =   CorresponsalDiario::where('Id_Moneda', $moneda->Id_Moneda)
                ->with('moneda', function ($q) {
                    $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
                })
                // ->where('Fecha', $this->getFecha())
                ->where('Identificacion_Funcionario', $this->id)
                ->select(
                    DB::raw('IF(sum(Consignacion) > 0, sum(Consignacion), 0) AS Ingreso_Total, Id_Moneda'),
                    DB::raw('IF(sum(Retiro) > 0, sum(Retiro), 0) AS Engreso_Total')
                )
                ->groupByRaw('Id_Moneda')
                ->first();

            array_push($coresponsalesArray['mov'],  $corresponsales);
        }
        $coresponsalesArray['Modulo'] = 'Corresponsales';
        return $coresponsalesArray;
    }

    public function ConsultarIngresosEgresosServicios()
    {
        $serviciosArray = [];
        $serviciosArray['mov'] = [];
        foreach ($this->getMonedas() as $moneda) {
            $servicios =   Servicio::where('Id_Moneda', $moneda->Id_Moneda)
                ->with('moneda', function ($q) {
                    $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
                })
                // ->where('Fecha', $this->getFecha())
                ->where('Identificacion_Funcionario', $this->id)
                ->select(
                    DB::raw('IF(sum(Valor + Comision) > 0, sum(Valor + Comision), 0) AS Ingreso_Total, Id_Moneda , "Ingreso" as Tipo '),
                    DB::raw('0 AS Egreso_Total')
                )
                ->groupByRaw('Id_Moneda')
                ->first();

            array_push($serviciosArray['mov'],  $servicios);
        }
        $serviciosArray['Modulo'] = 'Servicios';
        return  $serviciosArray;
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
