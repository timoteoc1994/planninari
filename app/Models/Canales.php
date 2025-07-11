<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canales extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'canales';
    protected $fillable = [
        'proyecto_id',
        'user_id',
        'canalescomunicacion',
        'canalesdistribucion',
        'facebook',
        'instagram',
        'whatsapp',
        'linkedin',
        'tiktok',
        'twitter',
        'youtube',
        'web',
        'otro',
    ];
}
