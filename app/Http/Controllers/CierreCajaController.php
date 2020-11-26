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
    public $result;

    public function  getInfo()
    {

        $this->result = collect();

        $this->id = request()->get("id");

        $this->result->push(['Monedas' => $this->getMonedas()]);

        $this->ConsultarIngresosEgresosCambios();

        $this->ConsultarIngresosEgresosTransferencias();

        $this->ConsultarIngresosEgresosGiros();

        $this->ConsultarIngresosEgresosTraslados();

        $this->ConsultarIngresosEgresosCorresponsal();

        $this->ConsultarIngresosEgresosServicios();

        return $this->result->toArray();
    }


    public function ConsultarIngresosEgresosCambios()
    {
        foreach ($this->getMonedas() as $moneda) {
            $cambios =   Cambio::where('Moneda_Origen', $moneda->Id_Moneda)
                ->with('moneda', function ($q) {
                    $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
                })
                // ->where('Fecha', $this->getFecha())
                ->where('Identificacion_Funcionario', $this->id)
                ->where('Estado', '<>', 'Anulado')
                ->select(
                    DB::raw('IF(sum(Valor_Origen) > 0, sum(Valor_Origen), 0) AS Ingreso_Total, Moneda_Origen'),
                    DB::raw('IF(sum(Valor_Destino) > 0, sum(Valor_Destino), 0) AS Egreso_Total')
                )
                ->groupByRaw('Moneda_Origen')
                ->get();

            $this->result->push(['Cambios' => $cambios]);
        }
    }
    public function ConsultarIngresosEgresosTransferencias()
    {
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
                ->get();

            $this->result->push(['Transferencias' => $transferencias]);
        }
    }
    public function ConsultarIngresosEgresosGiros()
    {
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
                ->get();

            $this->result->push(['Giros' => $giros]);
        }
    }
    public function ConsultarIngresosEgresosTraslados()
    {
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
                ->get();

            $this->result->push(['Traslados' => $traslados]);
        }
    }
    public function ConsultarIngresosEgresosCorresponsal()
    {
        foreach ($this->getMonedas() as $moneda) {
            $coresponsales =   CorresponsalDiario::where('Id_Moneda', $moneda->Id_Moneda)
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
                ->get();

            $this->result->push(['Corresponsales' => $coresponsales]);
        }
    }
    public function ConsultarIngresosEgresosServicios()
    {
        foreach ($this->getMonedas() as $moneda) {
            $servicios =   Servicio::where('Id_Moneda', $moneda->Id_Moneda)
                ->with('moneda', function ($q) {
                    $q->select('Codigo', 'Id_Moneda', 'Nombre as Moneda');
                })
                // ->where('Fecha', $this->getFecha())
                ->where('Identificacion_Funcionario', $this->id)
                ->select(
                    DB::raw('IF(sum(Valor + Comision) > 0, sum(Valor + Comision), 0) AS Ingreso_Total, Id_Moneda'),
                    DB::raw('0 AS Egreso_Total')
                )
                ->groupByRaw('Id_Moneda')
                ->get();

            $this->result->push(['servicios' => $servicios]);
        }
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
