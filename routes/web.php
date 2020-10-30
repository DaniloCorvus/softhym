<?php

use App\Http\Controllers\CambioController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\FomapagoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\GenericoController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\TerceroController;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


// Route::get('/conf', function () {
//     $index = 'Cambio';
//     $confi = Configuracion::first();
//     $confi->$index = $confi[$index] + 1;
//     $confi->save();
//     return  $confi["Prefijo_" . $index] . sprintf("%05d", $confi[$index]);
// });

// Route::get('/pass', function () {
//     return Hash::make('password');
// });

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
    Route::get('cuentasbancarias/buscar-cuentas-bancarias-por-moneda', [CuentaBancariaController::class, 'buscar']);
    //Terceros
    Route::get('terceros-filter', [TerceroController::class, 'filter']);
    Route::get('terceros', [TerceroController::class, 'index']);
    //Forma de pago 
    Route::get('foma-pago', [FomapagoController::class, 'index']);

    //CAmbios
    Route::post('cambios', [CambioController::class, 'store']);
    Route::get('cambios', [CambioController::class, 'index']);
    Route::get('print-cambio', [CambioController::class, 'printCambio']);
    Route::post('custom/cambios/update', [CambioController::class, 'update']);
});
