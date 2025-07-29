<?php

use App\Http\Controllers\Etapa3Controller;
use App\Http\Controllers\Etapa4Controller;
use App\Http\Controllers\Etapa5Controller;
use App\Http\Controllers\Etapa6Controller;
use App\Http\Controllers\Etapa7Controller;
use App\Http\Controllers\Etapa8Controller;
use App\Http\Controllers\RecetaEstandarController;
use App\Http\Controllers\MaterialInsumoController;
use App\Http\Controllers\CostoVariableController;
use App\Http\Controllers\CostoFijoController;
use App\Http\Controllers\PagoMensualController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\PuntoEquilibrioController;
use App\Http\Controllers\Etapa9Controller;
use App\Http\Controllers\Etapa10Controller;
use App\Http\Controllers\Etapas;
use App\Http\Controllers\IntegrantesController;
use App\Http\Controllers\PlanesController;
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


    Route::get('/dashboard', [ProjectStageController::class, 'index'])->name('dashboard');
    // Rutas para proyectos
    Route::post('projects', [ProjectStageController::class, 'projectsstore'])->name('projects.store');
    Route::post('/projects/{id}', [ProjectStageController::class, 'projectsupdate'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectStageController::class, 'projectsdestroy'])->name('projects.destroy');

    Route::get('/etapa1/{id}', [Etapas::class, 'etapa1'])->name('etapa1');
    Route::post('/etapa1/{proyecto_id}', [Etapas::class, 'storeOrUpdate'])->name('etapa1.storeOrUpdate');

    // Rutas para proyectos
    Route::get('/projects/{id}', [ProjectStageController::class, 'show'])->name('projects.stages.show');

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
    Route::get('/recursosclave/{id}', [Etapa4Controller::class, 'recursosclave'])
        ->name('index.recursosclave');
    Route::post('/recursosclave', [Etapa4Controller::class, 'recursosclavestore'])
        ->name('recursosclave.store');
    Route::post('/recursosclave/{id}', [Etapa4Controller::class, 'recursosclaveupdate'])
        ->name('recursosclave.update');

    // Hijos: Disponibles
    Route::get('/recursos-disponibles/{clave_id}', [Etapa4Controller::class, 'disponibles'])
        ->name('recursos.disponibles.index');
    Route::post('/recursos-disponibles', [Etapa4Controller::class, 'disponiblesStore'])
        ->name('recursos.disponibles.store');
    Route::put('/recursos-disponibles/{id}', [Etapa4Controller::class, 'disponiblesUpdate'])
        ->name('recursos.disponibles.update');
    Route::delete('/recursos-disponibles/{id}', [Etapa4Controller::class, 'disponiblesDelete'])
        ->name('recursos.disponibles.destroy');

    // Hijos: Necesarios
    Route::get('/recursos-necesarios/{clave_id}', [Etapa4Controller::class, 'necesarios'])
        ->name('recursos.necesarios.index');
    Route::post('/recursos-necesarios', [Etapa4Controller::class, 'necesariosStore'])
        ->name('recursos.necesarios.store');
    Route::put('/recursos-necesarios/{id}', [Etapa4Controller::class, 'necesariosUpdate'])
        ->name('recursos.necesarios.update');
    Route::delete('/recursos-necesarios/{id}', [Etapa4Controller::class, 'necesariosDelete'])
        ->name('recursos.necesarios.destroy');


    Route::get('/etapa7/{id}', [Etapa7Controller::class, 'inversiones'])
        ->name('etapa7.index');
    Route::post('/etapa7/inversiones',       [Etapa7Controller::class, 'inversionesStore'])
        ->name('etapa7.inversiones.store');
    Route::put('/etapa7/inversiones/{id}',  [Etapa7Controller::class, 'inversionesUpdate'])
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


    //canales
    Route::get('/canales/{id}', [Etapa4Controller::class, 'canales'])->name('index.canales');
    Route::post('/canales', [Etapa4Controller::class, 'canalesstore'])->name('canales.store');
    Route::post('/canales/{id}', [Etapa4Controller::class, 'canalesupdate'])->name('canales.update');

    //actividades clave
    Route::get('/actividadesclave/{id}', [Etapa4Controller::class, 'actividadesclave'])->name('index.actividadesclave');
    Route::post('/actividadesclave', [Etapa4Controller::class, 'actividadesclavestore'])->name('actividadesclave.store');
    Route::post('/actividadesclave/{id}', [Etapa4Controller::class, 'actividadesclaveupdate'])->name('actividadesclave.update');
    //sub rutas de activiadades clave -proceso productivo
    Route::post('/proceso-productivo', [Etapa4Controller::class, 'procesoProductivoStore'])->name('proceso-productivo.store');
    Route::post('/proceso-productivo/{id}', [Etapa4Controller::class, 'procesoProductivoUpdate'])->name('proceso-productivo.update');
    Route::delete('/proceso-productivo/{id}', [Etapa4Controller::class, 'procesoProductivoDestroy'])->name('proceso-productivo.destroy');
    //sub rutas de actividades clave -proceso comercial
    Route::post('/proceso-comercial', [Etapa4Controller::class, 'procesoComercialStore'])->name('proceso-comercial.store');
    Route::post('/proceso-comercial/{id}', [Etapa4Controller::class, 'procesoComercialUpdate'])->name('proceso-comercial.update');
    Route::delete('/proceso-comercial/{id}', [Etapa4Controller::class, 'procesoComercialDestroy'])->name('proceso-comercial.destroy');

    //rutas alianzas clave
    Route::get('/alianzasclave/{id}', [Etapa4Controller::class, 'alianzasclave'])->name('index.alianzasclave');
    Route::post('/alianzasclave', [Etapa4Controller::class, 'alianzasclavestore'])->name('alianzasclave.store');
    Route::post('/alianzasclave/{id}', [Etapa4Controller::class, 'alianzasclaveupdate'])->name('alianzasclave.update');
    Route::delete('/alianzasclave/{id}', [Etapa4Controller::class, 'alianzasclavedestroy'])->name('alianzasclave.destroy');

    // En routes/web.php
    Route::get('/etapa9/{proyecto_id}', [Etapa9Controller::class, 'index'])->name('etapa9');

    // Para crear
    Route::post('/etapa9', [Etapa9Controller::class, 'store'])
        ->name('etapa9.store');

    // Para actualizar - usar el ID del registro etapa9, no del proyecto
    Route::post('/etapa9/{etapa9}', [Etapa9Controller::class, 'update'])
        ->name('etapa9.update');
    // Nueva ruta para borrar sólo el video
    Route::delete('/etapa9/{etapa9}/video',     [Etapa9Controller::class, 'destroyVideo'])
        ->name('etapa9.destroyVideo');


    // Etapa 10
    Route::get('/etapa10/{proyecto_id}',       [Etapa10Controller::class, 'index'])->name('etapa10');
    Route::post('/etapa10',                     [Etapa10Controller::class, 'store'])->name('etapa10.store');
    Route::post('/etapa10/{etapa10}',           [Etapa10Controller::class, 'update'])->name('etapa10.update');
    // eliminar un documento por posición en el array
    Route::delete('/etapa10/{etapa10}/document/{index}', [Etapa10Controller::class, 'destroyDocument'])
        ->name('etapa10.destroyDocument');



    Route::get('/etapa8/{id}', [Etapa8Controller::class, 'index'])->name('etapa8');
    // Etapa 8 → Receta Estándar: Listado de Productos Desarrollados
    // Etapa 8 → Receta Estándar: Listado de Productos Desarrollados
    Route::get(
        '/proyecto/{id}/receta-estandar',
        [RecetaEstandarController::class, 'index']
    )
        ->name('recetaestandar.index');

    // (abrir modal, realmente redirige al índice)
    Route::get(
        '/proyecto/{id}/receta-estandar/create',
        [RecetaEstandarController::class, 'create']
    )
        ->name('recetaestandar.create');

    Route::post(
        '/proyecto/{id}/receta-estandar',
        [RecetaEstandarController::class, 'store']
    )
        ->name('recetaestandar.store');

    Route::get(
        '/proyecto/{id}/receta-estandar/{recetaestandar}',
        [RecetaEstandarController::class, 'show']
    )
        ->name('recetaestandar.show');

    Route::get(
        '/proyecto/{id}/receta-estandar/{recetaestandar}/edit',
        [RecetaEstandarController::class, 'edit']
    )
        ->name('recetaestandar.edit');

    Route::put(
        '/proyecto/{id}/receta-estandar/{recetaestandar}',
        [RecetaEstandarController::class, 'update']
    )
        ->name('recetaestandar.update');

    Route::delete(
        '/proyecto/{id}/receta-estandar/{recetaestandar}',
        [RecetaEstandarController::class, 'destroy']
    )
        ->name('recetaestandar.destroy');


    // Materiales e Insumos – Precio de Compra
    Route::get(
        '/proyecto/{id}/material-insumos',
        [MaterialInsumoController::class, 'index']
    )
        ->name('materialinsumo.index');

    Route::post(
        '/proyecto/{id}/material-insumos',
        [MaterialInsumoController::class, 'store']
    )
        ->name('materialinsumo.store');

    Route::put(
        '/proyecto/{id}/material-insumos/{materialinsumo}',
        [MaterialInsumoController::class, 'update']
    )
        ->name('materialinsumo.update');

    Route::delete(
        '/proyecto/{id}/material-insumos/{materialinsumo}',
        [MaterialInsumoController::class, 'destroy']
    )
        ->name('materialinsumo.destroy');




    // Costo Variable – Estimación de ventas
    Route::get(
        '/proyecto/{id}/costo-variable',
        [CostoVariableController::class, 'index']
    )->name('costovariable.index');

    Route::post(
        '/proyecto/{id}/costo-variable',
        [CostoVariableController::class, 'store']
    )->name('costovariable.store');

    Route::put(
        '/proyecto/{id}/costo-variable/{registro}',
        [CostoVariableController::class, 'update']
    )->name('costovariable.update');

    Route::delete(
        '/proyecto/{id}/costo-variable/{registro}',
        [CostoVariableController::class, 'destroy']
    )->name('costovariable.destroy');


    // routes/web.php
    Route::prefix('proyecto/{id}')->group(function () {
        Route::resource('empleados', EmpleadoController::class)
            ->except(['show', 'create', 'edit'])
            ->names('empleados');
    });


    Route::prefix('proyecto/{id}')->group(function () {
        Route::resource('costos-fijos', CostoFijoController::class)
            ->parameters(['costos-fijos' => 'costo_fijo']);
        Route::resource('pagos-mensuales', PagoMensualController::class)
            ->parameters(['pagos-mensuales' => 'pago_mensual']);
    });


    Route::get('proyecto/{id}/punto-equilibrio', [PuntoEquilibrioController::class, 'index'])->name('punto-equilibrio.index');
    Route::put('proyecto/{id}/punto-equilibrio/{punto_equilibrio}', [PuntoEquilibrioController::class, 'update'])->name('punto-equilibrio.update');


    //rutas usuario administrador con spatie
    Route::middleware(['auth', 'role:Administrador,Usuario'])->group(function () {
        Route::get('adminproyecto/', [PlanesController::class, 'index'])->name('planes.index');
       
    });
});
