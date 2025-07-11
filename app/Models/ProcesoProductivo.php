<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoProductivo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proceso_productivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'proyecto_id',
        'paso',
    ];

}
