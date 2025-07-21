<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('produtos.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Novo Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="nome" class="form-label">Nome</label>
          <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="preco" class="form-label">Preço (R$)</label>
          <input type="number" name="preco" id="preco" step="0.01" class="form-control" required>
        </div>
        <h6>Variações e Estoque</h6>
        <div id="newVariations">
          <div class="d-flex mb-2">
            <input name="variacoes[]" placeholder="Variação (opcional)" class="form-control me-2">
            <input name="quantidades[]" type="number" placeholder="Estoque" class="form-control" min="0">
          </div>
        </div>
        <button type="button" class="btn btn-link" onclick="addProductVariation()">+ Adicionar variação</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
      </div>
    </form>
  </div>
</div>

<script>
function addProductVariation() {
  const container = document.getElementById('newVariations');
  const row = document.createElement('div');
  row.classList.add('d-flex', 'mb-2');
  row.innerHTML = `
    <input name="variacoes[]" placeholder="Variação (opcional)" class="form-control me-2">
    <input name="quantidades[]" type="number" placeholder="Estoque" class="form-control" min="0">
  `;
  container.append(row);
}
</script>

<!-- Modal: Editar Produto -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editProductForm" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editProductModalLabel">Editar Produto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input name="nome" id="edit-nome" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Preço</label>
            <input name="preco" id="edit-preco" type="number" step="0.01" class="form-control" required>
          </div>
          
          <h6>Variações e Estoque</h6>
          <div id="editVariationsContainer">
            {{-- Linhas de variação serão injetadas aqui pelo JS --}}
          </div>
          <button type="button" class="btn btn-link btn-sm" id="addEditVariation">+ Adicionar variação</button>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const editProductModal = document.getElementById('editProductModal');
  const addEditVariationBtn = document.getElementById('addEditVariation');
  const variationsContainer = document.getElementById('editVariationsContainer');

  function createVariationRow(id = '', variacao = '', quantidade = '') {
    const row = document.createElement('div');
    row.classList.add('d-flex', 'mb-2', 'align-items-center');
    
    const newId = id || `new_${Math.random().toString(36).substr(2, 9)}`;

    row.innerHTML = `
      <input name="variacoes[${newId}][variacao]" placeholder="Variação (ex: Cor, Tamanho)" class="form-control me-2" value="${variacao}">
      <input name="variacoes[${newId}][quantidade]" type="number" placeholder="Estoque" class="form-control" min="0" value="${quantidade}">
      <button type="button" class="btn btn-danger btn-sm ms-2 remove-variation-btn">X</button>
    `;
    variationsContainer.appendChild(row);
  }

  addEditVariationBtn.addEventListener('click', () => createVariationRow());

  variationsContainer.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-variation-btn')) {
      e.target.closest('.d-flex').remove();
    }
  });

  if (editProductModal) {
    editProductModal.addEventListener('show.bs.modal', async function (event) {
      const button = event.relatedTarget;
      const productId = button.getAttribute('data-id');

      // Limpa variações anteriores
      variationsContainer.innerHTML = '';

      // Busca dados completos do produto, incluindo estoques
      const response = await fetch(`/api/produtos/${productId}`); // ATENÇÃO: Essa rota precisa ser criada
      const product = await response.json();

      const form = document.getElementById('editProductForm');
      const modalTitle = document.getElementById('editProductModalLabel');
      const inputName = document.getElementById('edit-nome');
      const inputPrice = document.getElementById('edit-preco');

      let action = '{{ route("produtos.update", ["produto" => ":id"]) }}';
      action = action.replace(':id', product.id);
      
      modalTitle.textContent = 'Editar ' + product.nome;
      form.action = action;
      inputName.value = product.nome;
      inputPrice.value = product.preco;

      if(product.estoques && product.estoques.length > 0) {
        product.estoques.forEach(e => {
          createVariationRow(e.id, e.variacao, e.quantidade);
        });
      } else {
        createVariationRow(); // Adiciona uma linha em branco se não houver estoque
      }
    });
  }
});
</script>