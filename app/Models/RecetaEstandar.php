<?php

// app/Models/RecetaEstandar.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecetaEstandar extends Model
{
    use HasFactory;

    protected $table = 'receta_estandars';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'ref',
        'nombre_producto',
        'detalle',
        'presentacion',
        'precio_venta',
    ];

    // Relaciones
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
