<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giro extends Model
{
    use HasFactory;
    protected  $table = 'Giro';

    public function moneda()
    {
        return $this->hasOne(Moneda::class, 'Id_Moneda', 'Id_Moneda');
    }
}
