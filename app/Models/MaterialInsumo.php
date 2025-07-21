<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecetaEstandar;

class MaterialInsumo extends Model
{
    use HasFactory;

    protected $table = 'material_insumos';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'ref',
        'ingrediente',
        'descripcion',
        'peso_g',
        'valor_usd',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación a la receta estándar (por código 'ref').
     */
    public function recetaEstandar()
    {
        // foreignKey es 'ref' en material_insumos,
        // ownerKey es 'ref' en receta_estandars
        return $this->belongsTo(recetaEstandar::class, 'ref', 'ref');
    }
}
