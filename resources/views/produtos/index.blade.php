@extends('layouts.app')
@section('title','Produtos')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between mb-4">
    <h1 class="h3">Produtos</h1>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#productModal">
      <i class="bi bi-plus-circle"></i> Novo
    </button>
  </div>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
    @foreach($produtos as $p)
      <div class="col">
        <div class="card h-100">
          <div class="card-body d-flex flex-column">
            <h5>{{ $p->nome }}</h5>
            <p><strong>R$ {{ number_format($p->preco,2,',','.') }}</strong></p>

            @if($p->estoques->count())
              <ul class="list-unstyled small mb-3">
                @foreach($p->estoques as $e)
                  <li>{{ $e->variacao ?? 'Padrão' }}: {{ $e->quantidade }}</li>
                @endforeach
              </ul>
            @endif

            <div class="mt-auto d-flex justify-content-between">
              <button class="btn btn-outline-primary btn-sm edit-product-btn"
                      data-bs-toggle="modal"
                      data-bs-target="#editProductModal"
                      data-id="{{ $p->id }}"
                      data-nome="{{ $p->nome }}"
                      data-preco="{{ $p->preco }}">
                <i class="bi bi-pencil"></i>
              </button>

              <form method="POST" action="{{ route('carrinho.adicionar', $p) }}" class="d-flex align-items-center gap-2">
                @csrf
                @if($p->estoques->count() > 1)
                  <select name="estoque_id" class="form-select form-select-sm w-auto" required>
                    @foreach($p->estoques as $e)
                      <option value="{{ $e->id }}">{{ $e->variacao ?? 'Padrão' }} ({{ $e->quantidade }})</option>
                    @endforeach
                  </select>
                @elseif($p->estoques->count() == 1)
                  <input type="hidden" name="estoque_id" value="{{ $p->estoques->first()->id }}">
                @endif
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="bi bi-cart-plus"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

@include('produtos._modals') 

@endsection
