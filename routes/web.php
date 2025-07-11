<?php

use App\Http\Controllers\Etapa3Controller;
use App\Http\Controllers\Etapa4Controller;
use App\Http\Controllers\Etapa5Controller;
use App\Http\Controllers\Etapa6Controller;
use App\Http\Controllers\Etapa7Controller;
use App\Http\Controllers\Etapas;
use App\Http\Controllers\IntegrantesController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectStageController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // Rutas para proyectos
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}', [ProjectStageController::class, 'show'])->name('projects.stages.show');

    Route::get('/etapa1/{id}', [Etapas::class, 'etapa1'])->name('etapa1');
    Route::post('/etapa1/{proyecto_id}', [Etapas::class, 'storeOrUpdate'])->name('etapa1.storeOrUpdate');

    //etapa 2
   Route::get('/proyectos/{proyecto_id}/integrantes', [IntegrantesController::class, 'index'])->name('integrantes.index');
    Route::get('/proyectos/{proyecto_id}/integrantes/create', [IntegrantesController::class, 'create'])->name('integrantes.create');
    Route::post('/proyectos/{proyecto_id}/integrantes', [IntegrantesController::class, 'store'])->name('integrantes.store');
    Route::get('/integrantes/{integrante}/edit', [IntegrantesController::class, 'edit'])->name('integrantes.edit');
    Route::put('/integrantes/{integrante}', [IntegrantesController::class, 'update'])->name('integrantes.update');
    Route::delete('/integrantes/{integrante}', [IntegrantesController::class, 'destroy'])->name('integrantes.destroy');

    Route::get('/etapa2/{id}', [IntegrantesController::class, 'index'])->name('etapa2');

    //etapa 3
    Route::get('/etapa3/{id}', [Etapa3Controller::class, 'index'])->name('etapa3');
    // Guardar nuevo registro de etapa3
    Route::post('/etapa3s', [Etapa3Controller::class, 'store'])->name('etapa3.store');
    // Actualizar registro existente de etapa3
    Route::put('/etapa3/{etapa3}', [Etapa3Controller::class, 'update'])->name('etapa3.update');

    //etapa4
    Route::get('/etapa4/{id}', [Etapa4Controller::class, 'index'])->name('etapa4');
    //rutas para propuesta de valor
    Route::get('/propuestavalor/{id}', [Etapa4Controller::class, 'propuestavalor'])->name('index.propuestavalor');
    Route::post('/propuestavalor', [Etapa4Controller::class, 'propuestavalorstore'])->name('propuestavalor.store');
    Route::post('/propuestavalor/{id}', [Etapa4Controller::class, 'propuestavalorupdate'])->name('propuestavalor.update');
    Route::delete('/propuesta-valor/{id}/imagen', [Etapa4Controller::class, 'eliminarImagen'])->name('propuestavalor.eliminar.imagen');

    // recursos clave
// RecursosClave (padre)
Route::get('/recursosclave/{id}', [Etapa4Controller::class,'recursosclave'])
     ->name('index.recursosclave');
Route::post('/recursosclave', [Etapa4Controller::class,'recursosclavestore'])
     ->name('recursosclave.store');
Route::post('/recursosclave/{id}', [Etapa4Controller::class,'recursosclaveupdate'])
     ->name('recursosclave.update');

// Hijos: Disponibles
Route::get('/recursos-disponibles/{clave_id}', [Etapa4Controller::class,'disponibles'])
     ->name('recursos.disponibles.index');
Route::post('/recursos-disponibles', [Etapa4Controller::class,'disponiblesStore'])
     ->name('recursos.disponibles.store');
Route::put('/recursos-disponibles/{id}', [Etapa4Controller::class,'disponiblesUpdate'])
     ->name('recursos.disponibles.update');
Route::delete('/recursos-disponibles/{id}', [Etapa4Controller::class,'disponiblesDelete'])
     ->name('recursos.disponibles.destroy');

// Hijos: Necesarios
Route::get('/recursos-necesarios/{clave_id}', [Etapa4Controller::class,'necesarios'])
     ->name('recursos.necesarios.index');
Route::post('/recursos-necesarios', [Etapa4Controller::class,'necesariosStore'])
     ->name('recursos.necesarios.store');
Route::put('/recursos-necesarios/{id}', [Etapa4Controller::class,'necesariosUpdate'])
     ->name('recursos.necesarios.update');
Route::delete('/recursos-necesarios/{id}', [Etapa4Controller::class,'necesariosDelete'])
     ->name('recursos.necesarios.destroy');


    Route::get('/etapa7/{id}', [Etapa7Controller::class, 'inversiones'])
         ->name('etapa7.index');
    Route::post  ('/etapa7/inversiones',       [Etapa7Controller::class, 'inversionesStore'])
         ->name('etapa7.inversiones.store');
    Route::put   ('/etapa7/inversiones/{id}',  [Etapa7Controller::class, 'inversionesUpdate'])
         ->name('etapa7.inversiones.update');
    Route::delete('/etapa7/inversiones/{id}',  [Etapa7Controller::class, 'inversionesDelete'])
         ->name('etapa7.inversiones.destroy');

    //rutas para segmento de clientes
    Route::get('/segmentoclientes/{id}', [Etapa4Controller::class, 'segmentoclientes'])->name('index.segmentoclientes');
    Route::post('/segmentoclientes', [Etapa4Controller::class, 'segmentoclientesstore'])->name('segmentoclientes.store');
    Route::post('/segmentoclientes/{id}', [Etapa4Controller::class, 'segmentoclientesupdate'])->name('segmentoclientes.update');

    //rutas para relacion con clientes
    Route::get('/relacionclientes/{id}', [Etapa4Controller::class, 'relacionclientes'])->name('index.relacionclientes');
    Route::post('/relacionclientes', [Etapa4Controller::class, 'relacionclientesstore'])->name('relacionclientes.store');
    Route::post('/relacionclientes/{id}', [Etapa4Controller::class, 'relacionclientesupdate'])->name('relacionclientes.update');

    Route::get('/etapa5/{proyecto}',  [Etapa5Controller::class, 'index'])->name('etapa5');
    Route::post('/etapa5',            [Etapa5Controller::class, 'store'])->name('etapa5.store');
    Route::put('/etapa5/{etapa5}',    [Etapa5Controller::class, 'update'])->name('etapa5.update');

    Route::get('/etapa6/{proyecto}',    [Etapa6Controller::class, 'index'])->name('etapa6');
    Route::post('/etapa6',              [Etapa6Controller::class, 'store'])->name('etapa6.store');
    Route::put('/etapa6/{etapa6}',      [Etapa6Controller::class, 'update'])->name('etapa6.update');

    
    Route::get('/etapa6/{id}', [Etapas::class, 'etapa6'])->name('etapa6');
    Route::get('/etapa8/{id}', [Etapas::class, 'etapa8'])->name('etapa8');
    Route::get('/etapa9/{id}', [Etapas::class, 'etapa9'])->name('etapa9');
    Route::get('/etapa10/{id}', [Etapas::class, 'etapa10'])->name('etapa10');

});
