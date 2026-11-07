<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SaidaController;
use App\Http\Controllers\RelatorioController;

// Rotas Públicas (Login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Rotas Protegidas por Autenticação
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Perfil do Usuário
    Route::get('/perfil', [AuthController::class, 'perfil'])->name('perfil');
    Route::post('/perfil', [AuthController::class, 'updatePerfil'])->name('perfil.update');

    // Painel de Gestão de Usuários (Somente Admin)
    Route::get('/usuarios', [AuthController::class, 'usuariosIndex'])->name('usuarios.index');
    Route::post('/usuarios', [AuthController::class, 'usuarioStore'])->name('usuarios.store');
    Route::put('/usuarios/{id}', [AuthController::class, 'usuarioUpdate'])->name('usuarios.update');
    Route::post('/usuarios/{id}/reset-senha', [AuthController::class, 'usuarioResetSenha'])->name('usuarios.reset-senha');
    Route::delete('/usuarios/{id}', [AuthController::class, 'usuarioDestroy'])->name('usuarios.destroy');

    // Dashboard
    Route::get('/', function () { 
        return redirect()->route('dashboard'); 
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/fluxo-saidas', [DashboardController::class, 'fluxoSaidas'])->name('api.fluxo');

    // Almoxarifado / Produtos
    Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
    Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

    // Saídas & Cautelas A4
    Route::get('/saidas', [SaidaController::class, 'index'])->name('saidas.index');
    Route::post('/saidas', [SaidaController::class, 'store'])->name('saidas.store');
    Route::get('/saidas/{id}/comprovante', [SaidaController::class, 'comprovante'])->name('saidas.comprovante');

    // Relatórios de Movimentação de TI
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('/relatorios/imprimir', [RelatorioController::class, 'imprimir'])->name('relatorios.imprimir');
});