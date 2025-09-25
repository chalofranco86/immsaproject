<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])
     ->middleware('auth')
     ->name('dashboard');

// Rutas para órdenes de trabajo
Route::prefix('ordenes-trabajo')->group(function () {
    Route::get('/', [OrdenTrabajoController::class, 'index'])->name('ordenes_trabajo.index');
    Route::get('/create', [OrdenTrabajoController::class, 'create'])->name('ordenes_trabajo.create');
    Route::post('/', [OrdenTrabajoController::class, 'store'])->name('ordenes_trabajo.store');
    Route::get('/{id}', [OrdenTrabajoController::class, 'show'])->name('ordenes_trabajo.show');
    Route::get('/{id}/edit', [OrdenTrabajoController::class, 'edit'])->name('ordenes_trabajo.edit');
    Route::put('/{id}', [OrdenTrabajoController::class, 'update'])->name('ordenes_trabajo.update');
    Route::get('/{id}/pdf', [OrdenTrabajoController::class, 'downloadPDF'])->name('ordenes_trabajo.pdf');
    Route::get('/generate/report', [OrdenTrabajoController::class, 'generateReport'])->name('ordenes_trabajo.generateReport');
    Route::patch('/ordenes_trabajo/{orden}/servicios/{servicio}/color', [OrdenTrabajoController::class, 'asignarColorServicio'])
        ->name('ordenes_trabajo.asignar_color');
    Route::post('/ordenes_trabajo/{orden}/servicios/{servicio}/horario-color', [OrdenTrabajoController::class, 'agregarHorarioColor'])
        ->name('ordenes_trabajo.agregar_horario_color');
    Route::delete('/ordenes_trabajo/horario-color/{horarioId}', [OrdenTrabajoController::class, 'eliminarHorarioColor'])
        ->name('ordenes_trabajo.eliminar_horario_color');
});

// Rutas para propietarios
Route::resource('propietarios', PropietarioController::class)->only(['index', 'create', 'store', 'edit', 'update']);
Route::delete('/propietarios/{propietario}', [PropietarioController::class, 'destroy'])->name('propietarios.destroy');

// Rutas para empleados
Route::resource('empleados', EmpleadoController::class)->only(['index', 'create', 'store', 'edit', 'update']);
Route::delete('/empleados/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');


// Rutas para servicios
Route::resource('servicios', ServicioController::class)->only(['index', 'create', 'store', 'edit', 'update']);
Route::delete('/servicios/{servicio}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

Route::patch('/ordenes_trabajo/{orden}/servicios/{servicio}/finalizado', [OrdenTrabajoController::class, 'marcarServicioFinalizado'])
    ->name('ordenes_trabajo.marcar_finalizado');

// Rutas para gestión de usuarios
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
});

Route::delete('/ordenes_trabajo/{orden}', [OrdenTrabajoController::class, 'destroy'])->name('ordenes_trabajo.destroy');

// Protección de rutas
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:supervisor,admin']);

Route::get('/supervisor/dashboard', function () {
    return view('supervisor.dashboard');
})->middleware(['auth', 'role:supervisor,admin']);
