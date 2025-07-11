<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoComercial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proceso_comercial';

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
