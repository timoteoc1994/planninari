<?php

// app/Models/Empleado.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
    'proyecto_id','user_id','nombre',
    'salario_nominal','beneficios_pct',
    'salario_real','es_empresario',    // ← lo añadimos
];

    public function proyecto()  { return $this->belongsTo(Proyecto::class); }
    public function user()      { return $this->belongsTo(User::class); }
}
