<?php

use App\Http\Controllers\CambioController;
use App\Http\Controllers\CierreCajaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CorresponsalController;
use App\Http\Controllers\DevolucioncambioController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\FomapagoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\GenericoController;
use App\Http\Controllers\GiroRemitenteController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\MotivodevolucioncambioController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\TerceroController;
use App\Models\Cambio;
use App\Models\Configuracion;
use App\Models\Devolucioncambio;
use App\Models\Funcionario;
use App\Models\GiroRemitente;
use App\Models\Tercero;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


Route::get('my', function () {
    $modulo = 'Cambio';
    $index = $modulo;
    $confi = Configuracion::first();
    $confi->$index = $confi[$index] + 1;
    $confi->save();
    return  $confi["Prefijo_" . $index] . sprintf("%05d", $confi[$index]);
});

Route::group(['middleware' => ['cors']], function () {
    Auth::routes();
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    //Funcionarios 
    Route::get('funcionarios', [FuncionarioController::class, 'index']);
    //Monedas
    Route::get('monedas', [MonedaController::class, 'index']);
    //Gnericos
    Route::get('genericos/paises', [GenericoController::class, 'paises']);
    Route::get('genericos/tipo-documento-extrangeros', [GenericoController::class, 'tipoDocumentosExtranjeros']);
    Route::get('genericos/tipo-documento-nacionales', [GenericoController::class, 'tipoDocumentoNacionales']);

    //Cuentas bancarias
    // Route::get('cuentasbancarias/buscar-cuentas-bancarias-por-moneda', [CuentaBancariaController::class, 'buscar']);
    //Terceros
    Route::get('terceros-filter', [TerceroController::class, 'filter']);
    Route::get('terceros', [TerceroController::class, 'index']);
    Route::get('terceros/filter/{tipos}', [TerceroController::class, 'tercerosFilter']);
    //Forma de pago 
    Route::get('foma-pago', [FomapagoController::class, 'index']);

    //Cambios
    Route::post('cambios', [CambioController::class, 'store']);
    Route::get('cambios', [CambioController::class, 'index']);
    Route::get('print-cambio', [CambioController::class, 'printCambio']);
    Route::post('custom/cambios/update', [CambioController::class, 'update']);
    Route::get('cambios/{id}', [CambioController::class, 'show']);



    //Devoluciones
    Route::get('motivos-devolucion', [MotivodevolucioncambioController::class, 'index']);
    Route::post('motivos-devolucion', [MotivodevolucioncambioController::class, 'store']);
    Route::post('motivos-devolucion/{id}', [MotivodevolucioncambioController::class, 'destroy']);

    Route::post('devolucion/store', [DevolucioncambioController::class, 'store']);
    Route::get('devoluciones/show/{Id_Cambio}', [DevolucioncambioController::class, 'show']);

    Route::resource('remitentes', GiroRemitenteController::class);
    Route::resource('egresos', EgresoController::class);

    Route::get('cierre-caja', [CierreCajaController::class, 'getInfo']);
    Route::post('cierre-caja/funcionario', [FuncionarioController::class, 'estadoCaja']);

    Route::post('corresponsales/update', [CorresponsalController::class, 'update']);
});
