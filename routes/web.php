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
use App\Http\Controllers\GastoGeneralController;
use App\Http\Controllers\ITController;
use App\Http\Controllers\IvaFacturaController;
use App\Http\Controllers\MaterialContratoController;
use App\Http\Controllers\MaterialEjecucionController;
use App\Http\Controllers\ManoObraContratoController;
use App\Http\Controllers\SubcontratoController;
use App\Http\Controllers\PagoSubcontratoController;
use App\Http\Controllers\ResidentController;

use App\Http\Middleware\ContractorMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



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
    Route::get('/proy/{proyecto}/materiales-contrato/{material}/edit', [MaterialContratoController::class, 'edit'])->name('materiales.contrato.edit');
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
    ]);

    // Pagos de subcontratos
    // Pagos de subcontratos
    Route::get('/subcontratos/{subcontrato}/pagos', [PagoSubcontratoController::class, 'index'])->name('pagos.index');
    Route::get('/subcontratos/{subcontrato}/pagos/create', [PagoSubcontratoController::class, 'create'])->name('pagos.create');
    Route::post('/subcontratos/{subcontrato}/pagos', [PagoSubcontratoController::class, 'store'])->name('pagos.store');
    Route::get('/subcontratos/{subcontrato}/pagos/{pago}/edit', [PagoSubcontratoController::class, 'edit'])->name('pagos.edit');
    Route::put('/subcontratos/{subcontrato}/pagos/{pago}', [PagoSubcontratoController::class, 'update'])->name('pagos.update');
    Route::delete('/subcontratos/{subcontrato}/pagos/{pago}', [PagoSubcontratoController::class, 'destroy'])->name('pagos.destroy');

    // Gastos generales
    Route::resource('proy/{proyecto}/gastos', GastoGeneralController::class)->names([
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
    ]);

    // IT (Impuesto a las Transferencias)
    Route::get('/proy/{proyecto}/it/edit', [ITController::class, 'edit'])->name('it.edit');
    Route::put('/proy/{proyecto}/it', [ITController::class, 'update'])->name('it.update');

    // IVA Facturas
    // IVA Facturas - accesible para contractor y residentes
    Route::resource('proy/{proyecto}/iva', IvaFacturaController::class)->names([
        'index' => 'iva.index',
        'create' => 'iva.create',
        'store' => 'iva.store',
        'edit' => 'iva.edit',
        'update' => 'iva.update',
        'destroy' => 'iva.destroy',
    ]);
    


});
// vistas del admin que no puede ver el residente
Route::middleware(['auth', ContractorMiddleware::class])->group(function () {
    Route::get('/residents/create', [ResidentController::class, 'create'])->name('residents.create');
    Route::post('/residents', [ResidentController::class, 'store'])->name('residents.store');
    // Proyectos
    Route::get('/proy', [ProjectController::class, 'index'])->name('proy.index');
    Route::get('/proy/create', [ProjectController::class, 'create'])->name('proy.create');
    Route::post('/proy', [ProjectController::class, 'store'])->name('proy.store');
    Route::get('/proy/{proyecto}/edit', [ProjectController::class, 'edit'])->name('proy.edit');
    Route::put('/proy/{proyecto}', [ProjectController::class, 'update'])->name('proy.update');
    Route::delete('/proy/{proyecto}', [ProjectController::class, 'destroy'])->name('proy.destroy');
    //Route::get('/proy/{proyecto}', [ProjectController::class, 'show'])->name('proy.show');
    Route::get('/proy/{proyecto}/comparacion', [ProjectController::class, 'comparacion'])->name('proy.comparacion');



    

     
});



require __DIR__.'/auth.php';
