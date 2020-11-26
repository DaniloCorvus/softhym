<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;
    protected $table = 'Egreso';
    protected $fillable = [
        'Id_Egreso',
        'Id_Grupo',
        'Id_Tercero',
        'Id_Moneda',
        'Valor',
        'Detalle',
        'Identificacion_Funcionario',
        'Fecha',
        'Estado',
        'Codigo'
    ];
}
