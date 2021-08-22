<?php

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

Route::get('/', [\App\Http\Controllers\HomeController::class, 'home'])->name('site.home');
Route::get('/contato', [\App\Http\Controllers\ContactController::class, 'contact'])->name('site.contact');
Route::get('/sobre-nos', [\App\Http\Controllers\AboutUsController::class, 'aboutus'])->name('site.aboutUs');
Route::get('/login', function() { return 'login'; })->name('site.login');

Route::prefix('/app')->group(function(){
    Route::get('/clientes', function() { return 'clientes'; })->name('site.clients');
    Route::get('/projetos', function() { return 'projetos'; })->name('site.projects');
    Route::get('/cursos', function() { return 'cursos'; })->name('site.courses');
});
