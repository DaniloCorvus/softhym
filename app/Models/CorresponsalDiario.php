<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorresponsalDiario extends Model
{
    use HasFactory;

    protected  $table = 'Corresponsal_Diario_Nuevo';

    public function moneda()
    {
        return $this->hasOne(Moneda::class, 'Id_Moneda', 'Moneda');
    }
}
