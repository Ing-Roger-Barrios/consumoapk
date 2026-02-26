<?php

use App\Http\Controllers\ListMaterialsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewprojectController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers;
use App\Http\Controllers\BeneficioSocialController;
use App\Http\Controllers\CompraMaterialsController;
use App\Http\Controllers\EquipoMaquinariaContratoController;
use App\Http\Controllers\EquipoMaquinariaEjecucionController;
use App\Http\Controllers\GastoGeneralController;
use App\Http\Controllers\ITController;
use App\Http\Controllers\IvaFacturaController;
use App\Http\Controllers\MaterialContratoController;
use App\Http\Controllers\MaterialEjecucionController;
use App\Http\Controllers\ManoObraContratoController;
use App\Http\Controllers\SubcontratoController;
use App\Http\Controllers\PagoSubcontratoController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\LibroContableController;
use App\Http\Controllers\ManoObraJornalController;
use App\Http\Controllers\ManoObraItemController;
use App\Http\Middleware\ContractorMiddleware;
use App\Http\Controllers\ManoObraModuloController;
use App\Http\Controllers\PlanillaAvanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/test-cloudinary', function () {
    dd(config('cloudinary'));
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');
    Route::get('/newproject', [NewprojectController::class, 'proy'])->name('newproy');

    Route::get('/project/{proyecto}', [ProjectController::class, 'project'])->name('proy');
    
    Route::get('/listmateriales/{proyecto}', [ListMaterialsController::class, 'listmat'])->name('mat.list');
    Route::get('/compramateriales/{proyecto}/{descripcion}/{unidad}', [CompraMaterialsController::class, 'compmat'])->name('mat.compra');




    // Materiales de contrato
    Route::get('/proy/{proyecto}/materiales-contrato', [MaterialContratoController::class, 'index'])->name('materiales.contrato.index');
    Route::get('/proy/{proyecto}/materiales-contrato/create', [MaterialContratoController::class, 'create'])->name('materiales.contrato.create');
    Route::post('/proy/{proyecto}/materiales-contrato', [MaterialContratoController::class, 'store'])->name('materiales.contrato.store');
    Route::get('/proy/{proyecto}/materiales-contrato/{materialdesc}/edit', [MaterialContratoController::class, 'edit'])->name('materiales.contrato.edit');
    Route::put('/proy/{proyecto}/materiales-contrato/{material}', [MaterialContratoController::class, 'update'])->name('materiales.contrato.update');
    Route::delete('/proy/{proyecto}/materiales-contrato/{material}', [MaterialContratoController::class, 'destroy'])->name('materiales.contrato.destroy');

    // Materiales en ejecución
    Route::get('/proy/{proyecto}/materiales-ejecucion', [MaterialEjecucionController::class, 'index'])->name('materiales.ejecucion.index');
    Route::get('/proy/{proyecto}/materiales-ejecucion/create', [MaterialEjecucionController::class, 'create'])->name('materiales.ejecucion.create');
    Route::post('/proy/{proyecto}/materiales-ejecucion', [MaterialEjecucionController::class, 'store'])->name('materiales.ejecucion.store');
    Route::get('/proy/{proyecto}/materiales-ejecucion/{material}/edit', [MaterialEjecucionController::class, 'edit'])->name('materiales.ejecucion.edit');
    Route::put('/proy/{proyecto}/materiales-ejecucion/{material}', [MaterialEjecucionController::class, 'update'])->name('materiales.ejecucion.update');
    Route::delete('/proy/{proyecto}/materiales-ejecucion/{material}', [MaterialEjecucionController::class, 'destroy'])->name('materiales.ejecucion.destroy');

    // Dentro del grupo contractor
    Route::post('/proy/{proyecto}/materiales-contrato/import', [MaterialContratoController::class, 'import'])
        ->name('materiales.contrato.import');


// Mano de obra contrato
    Route::get('/proy/{proyecto}/mano-obra-contrato', [ManoObraContratoController::class, 'index'])->name('mano.obra.contrato.index');
    Route::get('/proy/{proyecto}/mano-obra-contrato/create', [ManoObraContratoController::class, 'create'])->name('mano.obra.contrato.create');
    Route::post('/proy/{proyecto}/mano-obra-contrato', [ManoObraContratoController::class, 'store'])->name('mano.obra.contrato.store');
    Route::get('/proy/{proyecto}/mano-obra-contrato/{manoObraContrato}/edit', [ManoObraContratoController::class, 'edit'])->name('mano.obra.contrato.edit');
    Route::put('/proy/{proyecto}/mano-obra-contrato/{manoObraContrato}', [ManoObraContratoController::class, 'update'])->name('mano.obra.contrato.update');
    Route::delete('/proy/{proyecto}/mano-obra-contrato/{manoObraContrato}', [ManoObraContratoController::class, 'destroy'])->name('mano.obra.contrato.destroy');

    // Subcontratos
    Route::get('/proy/{proyecto}/subcontratos', [SubcontratoController::class, 'index'])->name('subcontratos.index');
    Route::get('/proy/{proyecto}/subcontratos/create', [SubcontratoController::class, 'create'])->name('subcontratos.create');
    Route::post('/proy/{proyecto}/subcontratos', [SubcontratoController::class, 'store'])->name('subcontratos.store');
    Route::get('/proy/{proyecto}/subcontratos/{subcontrato}/edit', [SubcontratoController::class, 'edit'])->name('subcontratos.edit');
    Route::put('/proy/{proyecto}/subcontratos/{subcontrato}', [SubcontratoController::class, 'update'])->name('subcontratos.update');
    Route::delete('/proy/{proyecto}/subcontratos/{subcontrato}', [SubcontratoController::class, 'destroy'])->name('subcontratos.destroy');

    /* Mano de obra contrato
    Route::resource('proy/{proyecto}/mano-obra-contrato', ManoObraContratoController::class)->names([
        'index' => 'mano.obra.contrato.index',
        'create' => 'mano.obra.contrato.create',
        'store' => 'mano.obra.contrato.store',
        'edit' => 'mano.obra.contrato.edit',
        'update' => 'mano.obra.contrato.update',
        'destroy' => 'mano.obra.contrato.destroy',
    ]);

    // Subcontratos
    Route::resource('proy/{proyecto}/subcontratos', SubcontratoController::class)->names([
        'index' => 'subcontratos.index',
        'create' => 'subcontratos.create',
        'store' => 'subcontratos.store',
        'edit' => 'subcontratos.edit',
        'update' => 'subcontratos.update',
        'destroy' => 'subcontratos.destroy',
    ]);  */

    // Pagos de subcontratos
    // Pagos de subcontratos
    Route::get('/subcontratos/{subcontrato}/pagos', [PagoSubcontratoController::class, 'index'])->name('pagos.index');
    Route::get('/subcontratos/{subcontrato}/pagos/create', [PagoSubcontratoController::class, 'create'])->name('pagos.create');
    Route::post('/subcontratos/{subcontrato}/pagos', [PagoSubcontratoController::class, 'store'])->name('pagos.store');
    Route::get('/subcontratos/{subcontrato}/pagos/{pago}/edit', [PagoSubcontratoController::class, 'edit'])->name('pagos.edit');
    Route::put('/subcontratos/{subcontrato}/pagos/{pago}', [PagoSubcontratoController::class, 'update'])->name('pagos.update');
    Route::delete('/subcontratos/{subcontrato}/pagos/{pago}', [PagoSubcontratoController::class, 'destroy'])->name('pagos.destroy');

    // Gastos generales
    Route::get('/proy/{proyecto}/gastos', [GastoGeneralController::class, 'index'])->name('gastos.index');
    Route::get('/proy/{proyecto}/gastos/create', [GastoGeneralController::class, 'create'])->name('gastos.create');
    Route::post('/proy/{proyecto}/gastos', [GastoGeneralController::class, 'store'])->name('gastos.store');
    Route::get('/proy/{proyecto}/gastos/{gasto}/edit', [GastoGeneralController::class, 'edit'])->name('gastos.edit');
    Route::put('/proy/{proyecto}/gastos/{gasto}', [GastoGeneralController::class, 'update'])->name('gastos.update');
    Route::delete('/proy/{proyecto}/gastos/{gasto}', [GastoGeneralController::class, 'destroy'])->name('gastos.destroy');

    // Beneficios sociales
    Route::get('/proy/{proyecto}/beneficios', [BeneficioSocialController::class, 'index'])->name('beneficios.index');
    Route::get('/proy/{proyecto}/beneficios/create', [BeneficioSocialController::class, 'create'])->name('beneficios.create');
    Route::post('/proy/{proyecto}/beneficios', [BeneficioSocialController::class, 'store'])->name('beneficios.store');
    Route::get('/proy/{proyecto}/beneficios/{beneficio}/edit', [BeneficioSocialController::class, 'edit'])->name('beneficios.edit');
    Route::put('/proy/{proyecto}/beneficios/{beneficio}', [BeneficioSocialController::class, 'update'])->name('beneficios.update');
    Route::delete('/proy/{proyecto}/beneficios/{beneficio}', [BeneficioSocialController::class, 'destroy'])->name('beneficios.destroy');
    // Gastos generales
    /*Route::resource('proy/{proyecto}/gastos', GastoGeneralController::class)->names([
        'index' => 'gastos.index',
        'create' => 'gastos.create',
        'store' => 'gastos.store',
        'edit' => 'gastos.edit',
        'update' => 'gastos.update',
        'destroy' => 'gastos.destroy',
    ]);

    // Beneficios sociales
    Route::resource('proy/{proyecto}/beneficios', BeneficioSocialController::class)->names([
        'index' => 'beneficios.index',
        'create' => 'beneficios.create',
        'store' => 'beneficios.store',
        'edit' => 'beneficios.edit',
        'update' => 'beneficios.update',
        'destroy' => 'beneficios.destroy',
    ]);*/

    // IT (Impuesto a las Transferencias)
    Route::get('/proy/{proyecto}/it/edit', [ITController::class, 'edit'])->name('it.edit');
    Route::put('/proy/{proyecto}/it', [ITController::class, 'update'])->name('it.update');

    // IVA Facturas
    Route::get('/proy/{proyecto}/iva', [IvaFacturaController::class, 'index'])->name('iva.index');
    Route::get('/proy/{proyecto}/iva/create', [IvaFacturaController::class, 'create'])->name('iva.create');
    Route::post('/proy/{proyecto}/iva', [IvaFacturaController::class, 'store'])->name('iva.store');
    Route::get('/proy/{proyecto}/iva/{factura}/edit', [IvaFacturaController::class, 'edit'])->name('iva.edit');
    Route::put('/proy/{proyecto}/iva/{factura}', [IvaFacturaController::class, 'update'])->name('iva.update');
    Route::delete('/proy/{proyecto}/iva/{factura}', [IvaFacturaController::class, 'destroy'])->name('iva.destroy');

    // Equipo y maquinaria contrato
    Route::get('/proy/{proyecto}/equipo-contrato', [EquipoMaquinariaContratoController::class, 'index'])->name('equipo.contrato.index');
    Route::get('/proy/{proyecto}/equipo-contrato/create', [EquipoMaquinariaContratoController::class, 'create'])->name('equipo.contrato.create');
    Route::post('/proy/{proyecto}/equipo-contrato', [EquipoMaquinariaContratoController::class, 'store'])->name('equipo.contrato.store');
    Route::get('/proy/{proyecto}/equipo-contrato/{equipo}/edit', [EquipoMaquinariaContratoController::class, 'edit'])->name('equipo.contrato.edit');
    Route::put('/proy/{proyecto}/equipo-contrato/{equipo}', [EquipoMaquinariaContratoController::class, 'update'])->name('equipo.contrato.update');
    Route::delete('/proy/{proyecto}/equipo-contrato/{equipo}', [EquipoMaquinariaContratoController::class, 'destroy'])->name('equipo.contrato.destroy');

    // Equipo y maquinaria ejecucion
    Route::get('/proy/{proyecto}/{descripcion}/{unidad}/equipo-ejecucion', [EquipoMaquinariaEjecucionController::class, 'index'])->name('equipo.ejecucion.index');
    Route::get('/proy/{proyecto}/equipo-ejecucion/create', [EquipoMaquinariaEjecucionController::class, 'create'])->name('equipo.ejecucion.create');
    Route::post('/proy/{proyecto}/equipo-ejecucion', [EquipoMaquinariaEjecucionController::class, 'store'])->name('equipo.ejecucion.store');
    Route::get('/proy/{proyecto}/equipo-ejecucion/{equipo}/edit', [EquipoMaquinariaEjecucionController::class, 'edit'])->name('equipo.ejecucion.edit');
    Route::put('/proy/{proyecto}/equipo-ejecucion/{equipo}', [EquipoMaquinariaEjecucionController::class, 'update'])->name('equipo.ejecucion.update');
    Route::delete('/proy/{proyecto}/equipo-ejecucion/{equipo}', [EquipoMaquinariaEjecucionController::class, 'destroy'])->name('equipo.ejecucion.destroy');
    



    // Libro Contable - Balance general
    Route::get('/proy/{proyecto}/libro', [LibroContableController::class, 'index'])
        ->name('libro.index');

    // Planillas de Pago (Ingresos)
    Route::get('/proy/{proyecto}/libro/planillas', [LibroContableController::class, 'planillasIndex'])
        ->name('libro.planillas.index');
    Route::get('/proy/{proyecto}/libro/planillas/create', [LibroContableController::class, 'planillasCreate'])
        ->name('libro.planillas.create');
    Route::post('/proy/{proyecto}/libro/planillas', [LibroContableController::class, 'planillasStore'])
        ->name('libro.planillas.store');
    Route::get('/proy/{proyecto}/libro/planillas/{planilla}/edit', [LibroContableController::class, 'planillasEdit'])
        ->name('libro.planillas.edit');
    Route::put('/proy/{proyecto}/libro/planillas/{planilla}', [LibroContableController::class, 'planillasUpdate'])
        ->name('libro.planillas.update');
    Route::delete('/proy/{proyecto}/libro/planillas/{planilla}', [LibroContableController::class, 'planillasDestroy'])
        ->name('libro.planillas.destroy');




        // ── Hub principal de Mano de Obra ──────────────────────────
Route::get('/proy/{proyecto}/mano-obra', function(\App\Models\Proyecto $proyecto) {
    return view('mano-obra.index', compact('proyecto'));
})->name('mano.obra.hub');

// ── JORNAL ─────────────────────────────────────────────────
Route::prefix('proy/{proyecto}/jornal')->group(function () {

    // Trabajadores
    Route::get('/trabajadores',             [ManoObraJornalController::class, 'trabajadores'])->name('jornal.trabajadores');
    Route::post('/trabajadores',            [ManoObraJornalController::class, 'asignarTrabajador'])->name('jornal.asignar');
    Route::delete('/trabajadores/{trabajador}', [ManoObraJornalController::class, 'desasignarTrabajador'])->name('jornal.desasignar');

    // Planillas
    Route::get('/planillas',                [ManoObraJornalController::class, 'planillas'])->name('jornal.planillas');
    Route::get('/planillas/create',         [ManoObraJornalController::class, 'createPlanilla'])->name('jornal.create_planilla');
    Route::post('/planillas',               [ManoObraJornalController::class, 'storePlanilla'])->name('jornal.store_planilla');
    Route::get('/planillas/{planilla}',     [ManoObraJornalController::class, 'showPlanilla'])->name('jornal.show_planilla');
    Route::delete('/planillas/{planilla}',  [ManoObraJornalController::class, 'destroyPlanilla'])->name('jornal.destroy_planilla');
});

// ── POR ÍTEM ───────────────────────────────────────────────
Route::prefix('proy/{proyecto}/mano-obra-item')->group(function () {

    Route::get('/',                             [ManoObraItemController::class, 'index'])->name('mano.obra.item.index');
    Route::get('/create',                       [ManoObraItemController::class, 'createAsignacion'])->name('mano.obra.item.create_asignacion');
    Route::post('/',                            [ManoObraItemController::class, 'storeAsignacion'])->name('mano.obra.item.store_asignacion');
    Route::get('/{asignacion}',                 [ManoObraItemController::class, 'showAsignacion'])->name('mano.obra.item.show');
    Route::get('/{asignacion}/avance',          [ManoObraItemController::class, 'createAvance'])->name('mano.obra.item.create_avance');
    Route::post('/{asignacion}/avance',         [ManoObraItemController::class, 'storeAvance'])->name('mano.obra.item.store_avance');
    Route::delete('/avance/{avance}',           [ManoObraItemController::class, 'destroyAvance'])->name('mano.obra.item.destroy_avance');
});

Route::prefix('proy/{proyecto}/modulos')->group(function () {
    // Índice y operaciones de módulos
    Route::get('/',                              [ManoObraModuloController::class, 'index'])->name('mano.obra.modulos.index');
    Route::get('/create',                        [ManoObraModuloController::class, 'createModulo'])->name('mano.obra.modulos.create_modulo');
    Route::post('/',                             [ManoObraModuloController::class, 'storeModulo'])->name('mano.obra.modulos.store_modulo');
    Route::get('/{modulo}',                      [ManoObraModuloController::class, 'showModulo'])->name('mano.obra.modulos.show');
    Route::delete('/{modulo}',                   [ManoObraModuloController::class, 'destroyModulo'])->name('mano.obra.modulos.destroy_modulo');

    // Importar Excel
    Route::post('/import',                       [ManoObraModuloController::class, 'import'])->name('mano.obra.modulos.import');

    // Ítems dentro de módulo
    Route::get('/{modulo}/items/create',         [ManoObraModuloController::class, 'createItem'])->name('mano.obra.modulos.create_item');
    Route::post('/{modulo}/items',               [ManoObraModuloController::class, 'storeItem'])->name('mano.obra.modulos.store_item');
    Route::delete('/items/{item}',               [ManoObraModuloController::class, 'destroyItem'])->name('mano.obra.modulos.destroy_item');
});


Route::prefix('proyectos/{proyecto}')
    ->group(function () {

    Route::get('/planillas-avance', 
        [PlanillaAvanceController::class, 'index'])
        ->name('planilla_avance.index');

    Route::get('/planillas-avance/create', 
        [PlanillaAvanceController::class, 'create'])
        ->name('planilla_avance.create');

    Route::post('/planillas-avance', 
        [PlanillaAvanceController::class, 'store'])
        ->name('planilla_avance.store');
});

Route::get('/planillas-avance/{planilla}', 
    [PlanillaAvanceController::class, 'show'])
    ->name('planilla_avance.show');

Route::post('/planillas-avance/{planilla}/constancia', 
    [PlanillaAvanceController::class, 'subirConstancia'])
    ->name('planilla_avance.subirConstancia');

    

});
// vistas del admin que no puede ver el residente
Route::middleware(['auth', ContractorMiddleware::class])->group(function () {    
     
    // Proyectos
    Route::get('/proy', [ProjectController::class, 'index'])->name('proy.index');
    Route::get('/proy/create', [ProjectController::class, 'create'])->name('proy.create');
    Route::post('/proy', [ProjectController::class, 'store'])->name('proy.store');
    Route::get('/proy/{proyecto}/edit', [ProjectController::class, 'edit'])->name('proy.edit');
    Route::put('/proy/{proyecto}', [ProjectController::class, 'update'])->name('proy.update');
    Route::delete('/proy/{proyecto}', [ProjectController::class, 'destroy'])->name('proy.destroy');
    //Route::get('/proy/{proyecto}', [ProjectController::class, 'show'])->name('proy.show');
    Route::get('/proy/{proyecto}/comparacion', [ProjectController::class, 'comparacion'])->name('proy.comparacion');

    // Residentes
    Route::get('/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/create', [ResidentController::class, 'create'])->name('residents.create');
    Route::post('/residents', [ResidentController::class, 'store'])->name('residents.store');
    Route::get('/residents/{resident}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
    Route::put('/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');
    Route::delete('/residents/{resident}', [ResidentController::class, 'destroy'])->name('residents.destroy');

    

     
});



require __DIR__.'/auth.php';
