<div class="modal fade" id="cupomModal" tabindex="-1" aria-labelledby="cupomModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('cupons.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="cupomModalLabel">Novo Cupom</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="codigo" class="form-label">Código</label>
          <input type="text" name="codigo" id="codigo" class="form-control" required>
        </div>
        <div class="row">
          <div class="col mb-3">
            <label for="desconto_valor" class="form-label">Valor Fixo (R$)</label>
            <input type="number" step="0.01" name="desconto_valor" id="desconto_valor" class="form-control">
          </div>
          <div class="col mb-3">
            <label for="desconto_percentual" class="form-label">Percentual (%)</label>
            <input type="number" step="0.01" name="desconto_percentual" id="desconto_percentual" class="form-control">
          </div>
        </div>
        <div class="mb-3">
          <label for="valor_minimo" class="form-label">Valor Mínimo (R$)</label>
          <input type="number" step="0.01" name="valor_minimo" id="valor_minimo" class="form-control" value="0" required>
        </div>
        <div class="mb-3">
          <label for="data_validade" class="form-label">Data de Validade</label>
          <input type="date" name="data_validade" id="data_validade" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modals de Editar Cupom -->
@foreach($cupons as $cupom)
<div class="modal fade" id="cupomModal-{{ $cupom->id }}" tabindex="-1" aria-labelledby="cupomModalLabel-{{ $cupom->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('cupons.update', $cupom) }}" method="POST" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="cupomModalLabel-{{ $cupom->id }}">Editar Cupom</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="codigo-{{ $cupom->id }}" class="form-label">Código</label>
          <input type="text" name="codigo" id="codigo-{{ $cupom->id }}" class="form-control" value="{{ $cupom->codigo }}" required>
        </div>
        <div class="row">
          <div class="col mb-3">
            <label for="desconto_valor-{{ $cupom->id }}" class="form-label">Valor Fixo (R$)</label>
            <input type="number" step="0.01" name="desconto_valor" id="desconto_valor-{{ $cupom->id }}" class="form-control" value="{{ $cupom->desconto_valor }}">
          </div>
          <div class="col mb-3">
            <label for="desconto_percentual-{{ $cupom->id }}" class="form-label">Percentual (%)</label>
            <input type="number" step="0.01" name="desconto_percentual" id="desconto_percentual-{{ $cupom->id }}" class="form-control" value="{{ $cupom->desconto_percentual }}">
          </div>
        </div>
        <div class="mb-3">
          <label for="valor_minimo-{{ $cupom->id }}" class="form-label">Valor Mínimo (R$)</label>
          <input type="number" step="0.01" name="valor_minimo" id="valor_minimo-{{ $cupom->id }}" class="form-control" value="{{ $cupom->valor_minimo }}" required>
        </div>
        <div class="mb-3">
          <label for="data_validade-{{ $cupom->id }}" class="form-label">Data de Validade</label>
          <input type="date" name="data_validade" id="data_validade-{{ $cupom->id }}" class="form-control" value="{{ $cupom->data_validade->format('Y-m-d') }}" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Atualizar</button>
      </div>
    </form>
  </div>
</div>
@endforeach