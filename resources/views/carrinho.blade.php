<div 
  class="offcanvas offcanvas-end" 
  tabindex="-1" 
  id="cartOffcanvas"
>
  <div class="offcanvas-header bg-primary text-white">
    <h5 class="offcanvas-title">Carrinho</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    @php
      $cart = session('cart', []);
      $subtotal = array_sum(array_map(fn($i)=>$i['quantidade']*$i['preco'],$cart));
      $frete = $subtotal>=52 && $subtotal<=166.59 ? 15 : ($subtotal>200?0:20);
      $cupom = session('cupom', ['desconto' => 0]);
      $desconto = $cupom['desconto'];
      $total = $subtotal + $frete - $desconto;
    @endphp

    @if(count($cart))
      <ul class="list-group mb-3">
        @foreach($cart as $item)
          <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">{{ $item['nome'] }}</h6>
              <small class="text-muted">Qtd: {{ $item['quantidade'] }}</small>
            </div>
            <span class="text-muted">R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</span>
          </li>
        @endforeach
        <li class="list-group-item d-flex justify-content-between">
          <span>Subtotal</span>
          <strong>R$ {{ number_format($subtotal, 2, ',', '.') }}</strong>
        </li>
        @if($desconto > 0)
        <li class="list-group-item d-flex justify-content-between bg-light">
          <div class="text-success">
            <h6 class="my-0">Cupom ({{ session('cupom.codigo') }})</h6>
            <small>DESCONTO</small>
          </div>
          <span class="text-success">−R$ {{ number_format($desconto, 2, ',', '.') }}</span>
        </li>
        @endif
        <li class="list-group-item d-flex justify-content-between">
          <span>Frete</span>
          <strong>R$ {{ number_format($frete, 2, ',', '.') }}</strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>Total</span>
          <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
        </li>
      </ul>

      <div class="card p-2">
        @if($desconto > 0)
          <div class="input-group">
            <input type="text" class="form-control text-success" readonly value="{{ session('cupom.codigo') }}">
            <form action="{{ route('carrinho.removerCupom') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-outline-danger">Remover</button>
            </form>
          </div>
        @else
          <form action="{{ route('carrinho.cupom') }}" method="POST">
            @csrf
            <input type="hidden" name="subtotal" value="{{ $subtotal }}">
            <div class="input-group">
              <input type="text" class="form-control" name="codigo" placeholder="Cupom de desconto">
              <button type="submit" class="btn btn-secondary">Aplicar</button>
            </div>
          </form>
        @endif
      </div>

      <hr class="my-4">

      <form method="POST" action="{{ route('pedido.finalizar') }}" id="checkoutForm">
        @csrf
        <input type="hidden" name="subtotal" value="{{ $subtotal }}">
        <input type="hidden" name="frete" value="{{ $frete }}">

        <div class="mb-2">
          <label class="form-label">CEP</label>
          <input type="text" name="cep" id="cep" class="form-control" maxlength="9" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Logradouro</label>
          <input type="text" name="logradouro" id="logradouro" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Bairro</label>
          <input type="text" name="bairro" id="bairro" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Cidade</label>
          <input type="text" name="cidade" id="cidade" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Estado</label>
          <input type="text" name="estado" id="estado" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">E-mail</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">
          Finalizar Pedido
        </button>
      </form>
      <script>
      document.getElementById('cep').addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');
        if (cep.length === 8) {
          fetch(`/viacep/${cep}`)
            .then(res => res.json())
            .then(data => {
              if (!data.erro) {
                document.getElementById('logradouro').value = data.logradouro || '';
                document.getElementById('bairro').value = data.bairro || '';
                document.getElementById('cidade').value = data.localidade || '';
                document.getElementById('estado').value = data.uf || '';
              }
            });
        }
      });
      </script>
    @else
      <p class="text-center">Carrinho vazio.</p>
    @endif
  </div>
</div>
