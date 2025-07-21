<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CupomController;

// Tela de produtos + criação/edição
Route::get('/', [ProdutoController::class, 'index'])->name('produtos.index');
Route::post('produtos', [ProdutoController::class, 'store'])->name('produtos.store');
Route::put('produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');

// Carrinho em sessão
Route::post('carrinho/adicionar/{produto}', [PedidoController::class, 'adicionarAoCarrinho'])->name('carrinho.adicionar');
Route::get('carrinho', [PedidoController::class, 'mostrarCarrinho'])->name('carrinho.mostrar');
Route::post('carrinho/aplicar-cupom', [PedidoController::class, 'aplicarCupom'])->name('carrinho.cupom');
Route::post('carrinho/remover-cupom', [PedidoController::class, 'removerCupom'])->name('carrinho.removerCupom');
Route::post('pedido/finalizar', [PedidoController::class, 'finalizar'])->name('pedido.finalizar');

// Consulta CEP
Route::get('viacep/{cep}', [PedidoController::class, 'consultaCep'])->name('viacep');

// Cupons e Pedidos (listagens)
Route::resource('cupons', CupomController::class)->except(['show']);
Route::resource('pedidos', PedidoController::class)->only(['index', 'update', 'destroy']);


Route::post('webhook/pedido', [WebhookController::class, 'handle']);
