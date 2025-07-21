@extends('layouts.app')
@section('title','Pedidos')
@section('content')
  <h1 class="h3 mb-4">Pedidos</h1>
  @if($pedidos->count())
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#</th><th>Data</th><th>Total</th><th>Status</th><th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pedidos as $p)
          <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
            <td>R$ {{ number_format($p->total,2,',','.') }}</td>
            <td><span class="badge bg-{{ $p->status=='cancelado'?'danger':'primary' }}">{{ ucfirst($p->status) }}</span></td>
            <td>
              @if($p->status !== 'cancelado')
              <form action="{{ route('pedidos.destroy', $p) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar este pedido?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="bi bi-x-circle"></i> Cancelar
                </button>
              </form>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p class="text-center">Nenhum pedido encontrado.</p>
  @endif
@endsection
