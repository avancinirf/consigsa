<?php

use Illuminate\Support\Facades\Route;
use App\Mail\MensagemTesteMail;
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

// Rotas públicas
Route::get('/', function () { return view('welcome'); });
Route::get('/sobre', function () { return view('site.sobre'); });
Route::get('/servicos', function () { return view('site.servicos'); });
Route::get('/blog', function () { return view('site.blog'); });
Route::get('/contato', function () { return view('site.contato'); }); //Route::get('/contato', [\App\Http\Controllers\ContactController::class, 'contact'])->name('site.contact');
Route::get('/login', function() { return 'login'; })->name('site.login');

// Rotas de teste de envio de e-mails
Route::get('/mensagem-teste', function() { return new MensagemTesteMail(); });
Route::get('/enviar-mensagem-teste', function() {
    Mail::to('avancini.rf@gmail.com')->send(new MensagemTesteMail());
    return 'E-mail teste enviado com sucesso!!!';
});

// Rota dashboard inicial da aplicação
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['auth', 'verified']);

// Rotas com autenticação
Route::prefix('/app')->middleware(['auth', 'verified', 'check.is.admin'])->group(function(){
    Route::get('/usuarios', function() { return 'usuarios'; })->name('app.usuarios');
    Route::get('/projetos', function() { return view('app.projetos.gestao'); })->name('app.projetos');
    //Route::get('/projetos', [\App\Http\Controllers\ProjectController::class, 'create'])->name('app.project.create');
    Route::get('/cursos', function() { return 'cursos'; })->name('app.cursos');
});


Auth::routes(['verify' => true]);

