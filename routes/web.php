<?php

use App\Http\Controllers\AdministradoresController;
use App\Http\Controllers\admins\AdminsAdministrativoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdministrativosController;
use App\Http\Controllers\Homeworks\CreateHomeworkController;
use App\Http\Controllers\Homeworks\DeleteHomeworkController;
use App\Http\Controllers\Homeworks\EditHomeworkController;
use App\Http\Controllers\Homeworks\MoveHomeworkController;
use App\Http\Controllers\Homeworks\StatusHomeworkController;
use App\Http\Controllers\Homeworks\UpdateHomeworkController;
use App\Http\Controllers\HomeworksController;
use App\Http\Controllers\Jefes\JefesAdministradorController;
use App\Http\Controllers\JefesHuertoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function(){

    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/administrativos', AdministrativosController::class)->name('administrativos');
    Route::get('/administradores', AdministradoresController::class)->name('administradores');
    Route::get('/jefes/{id}/admin', JefesAdministradorController::class)->name('jefes.admin');
    Route::get('/admins/{id}/administrativo', AdminsAdministrativoController::class)->name('admins.administrativo');
    Route::get('/jefes', JefesHuertoController::class)->name('jefes');

    Route::get('/homeworks', HomeworksController::class)->name('homeworks');
    Route::post('/homework/create', CreateHomeworkController::class)->name('homework.create');
    Route::get('/homework/{id}/edit', EditHomeworkController::class)->name('homework.edit');
    Route::put('/homework/{id}/update', UpdateHomeworkController::class)->name('homework.update');
    Route::put('/homework/{id}/status', StatusHomeworkController::class)->name('homework.status');
    Route::post('/homework/{id}/move', MoveHomeworkController::class)->name('homework.move');
    Route::delete('/homework/{id}/delete', DeleteHomeworkController::class)->name('homework.delete');
});
