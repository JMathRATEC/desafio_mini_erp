@extends('layouts.app')
@section('title','Cupons')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h3">Cupons</h1>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cupomModal">
    <i class="bi bi-plus-circle me-1"></i> Novo Cupom
  </button>
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Código</th>
      <th>Desconto</th>
      <th>Valor Mínimo</th>
      <th>Validade</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach($cupons as $c)
      <tr>
        <td>{{ $c->codigo }}</td>
        <td>
          @if($c->desconto_valor)
            R$ {{ number_format($c->desconto_valor,2,',','.') }}
          @else
            {{ $c->desconto_percentual }}%
          @endif
        </td>
        <td>R$ {{ number_format($c->valor_minimo,2,',','.') }}</td>
        <td>{{ $c->data_validade->format('d/m/Y') }}</td>
        <td>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#cupomModal-{{ $c->id }}">
            <i class="bi bi-pencil"></i>
          </button>
          <form action="{{ route('cupons.destroy',$c) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

@include('cupons._form_modal')

@endsection
