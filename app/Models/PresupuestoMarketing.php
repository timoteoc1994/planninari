<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoMarketing extends Model
{
    protected $table = 'presupuesto_marketing';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'medios',               // Array de objetos con {nombre, valores[8]}
        'costos_trimestrales',  // Array de 8 elementos con totales por trimestre
        'ganancias_trimestrales', // Array de 8 elementos con ganancias por trimestre
        'roi_dolares',          // Array de 8 elementos calculado
        'roi_porcentajes',      // Array de 8 elementos calculado
    ];

    protected $casts = [
        'medios' => 'array',
        'costos_trimestrales' => 'array',
        'ganancias_trimestrales' => 'array',
        'roi_dolares' => 'array',
        'roi_porcentajes' => 'array',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Método principal para calcular todos los totales y ROI
    public function calcularTotales()
    {
        // Calcular costos totales por trimestre
        $costosTotales = array_fill(0, 8, 0);
        
        if (!empty($this->medios)) {
            foreach ($this->medios as $medio) {
                if (isset($medio['valores']) && is_array($medio['valores'])) {
                    for ($i = 0; $i < 8; $i++) {
                        $costosTotales[$i] += floatval($medio['valores'][$i] ?? 0);
                    }
                }
            }
        }

        $this->costos_trimestrales = $costosTotales;

        // Calcular ROI
        $this->calcularRoi();

        $this->save();
        return $this;
    }

    // Método para calcular ROI en base a las ganancias y costos totales
    public function calcularRoi()
    {
        $roiDolares = array_fill(0, 8, 0);
        $roiPorcentajes = array_fill(0, 8, 0);

        for ($i = 0; $i < 8; $i++) {
            $costo = floatval($this->costos_trimestrales[$i] ?? 0);
            $ganancia = floatval($this->ganancias_trimestrales[$i] ?? 0);

            // ROI en dólares: Ganancia - Costo
            $roiDolares[$i] = $ganancia - $costo;

            // ROI en porcentaje: (Ganancia - Costo) / Costo * 100
            if ($costo > 0) {
                $roiPorcentajes[$i] = (($ganancia - $costo) / $costo) * 100;
            } else {
                $roiPorcentajes[$i] = $ganancia > 0 ? 100 : 0;
            }
        }

        $this->roi_dolares = $roiDolares;
        $this->roi_porcentajes = $roiPorcentajes;

        return $this;
    }

    // Método para obtener el total de presupuesto
    public function getTotalPresupuesto()
    {
        return array_sum($this->costos_trimestrales ?? []);
    }

    // Método para obtener el total de ganancias
    public function getTotalGanancias()
    {
        return array_sum($this->ganancias_trimestrales ?? []);
    }

    // Método para obtener el total de ROI en dólares
    public function getTotalRoiDolares()
    {
        return array_sum($this->roi_dolares ?? []);
    }

    // Método para obtener el ROI porcentual total
    public function getRoiPorcentajeTotal()
    {
        $totalCosto = $this->getTotalPresupuesto();
        $totalRoi = $this->getTotalRoiDolares();
        
        return $totalCosto > 0 ? ($totalRoi / $totalCosto) * 100 : 0;
    }
}