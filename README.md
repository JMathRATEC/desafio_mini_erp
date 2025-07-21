# Mini ERP - Desafio Técnico

Sistema de gestão de pedidos, produtos, cupons e estoque, desenvolvido em Laravel 12.

## Instruções do Desafio

- Crie um banco de dados com 4 tabelas: **pedidos**, **produtos**, **cupons**, **estoque**.
- Crie uma tela simples para cadastro de produtos (Nome, Preço, Variações e Estoque). O cadastro gera associações entre produtos e estoques. Permitir cadastro de variações e controle de estoque é um bônus.
- Na mesma tela, permita atualizar dados do produto e do estoque.
- Com o produto salvo, adicione na mesma tela um botão de Comprar. Ao clicar, gerencie um carrinho em sessão, controlando o estoque e valores do pedido.  
  - Subtotal entre R$52,00 e R$166,59: frete R$15,00  
  - Subtotal maior que R$200,00: frete grátis  
  - Outros valores: frete R$20,00
- Adicione verificação de CEP usando https://viacep.com.br/

### Pontos adicionais

- Crie cupons gerenciáveis por tela ou migração. Os cupons têm validade e regras de valor mínimo baseadas no subtotal do carrinho.
- Adicione envio de e-mail ao finalizar o pedido, com o endereço preenchido pelo cliente.
- Crie um webhook que recebe o ID e status do pedido. Se status for cancelado, remova o pedido. Se for outro, atualize o status.

---

## Como rodar o projeto

### 1. Instale as dependências

```bash
composer install
npm install
```

### 2. Configure o ambiente

Crie um arquivo `.env` na raiz do projeto com as configurações do seu banco de dados e e-mail. Exemplo para Mailtrap:

```dotenv
APP_NAME=MiniERP
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/para/database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_usuario_mailtrap
MAIL_PASSWORD=sua_senha_mailtrap
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="contato@minierp.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

### 3. Crie o banco de dados

Você pode rodar as migrations normalmente:

```bash
php artisan migrate --seed
```

Ou, se quiser criar as tabelas manualmente, use o SQL abaixo:

```sql
CREATE TABLE produtos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE estoque (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    produto_id INTEGER NOT NULL,
    variacao VARCHAR(255),
    quantidade INTEGER DEFAULT 0,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

CREATE TABLE cupons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    codigo VARCHAR(255) UNIQUE NOT NULL,
    desconto_valor DECIMAL(10,2),
    desconto_percentual DECIMAL(5,2),
    valor_minimo DECIMAL(10,2) DEFAULT 0,
    data_validade DATE NOT NULL,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE pedidos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    itens JSON NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    frete DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(255) DEFAULT 'novo',
    email VARCHAR(255) NOT NULL,
    cep VARCHAR(9) NOT NULL,
    logradouro VARCHAR(255) NOT NULL,
    bairro VARCHAR(255) NOT NULL,
    cidade VARCHAR(255) NOT NULL,
    estado VARCHAR(255) NOT NULL,
    created_at DATETIME,
    updated_at DATETIME
);
```

### 4. Rode o projeto

```bash
php artisan serve
```

Acesse em [http://localhost:8000](http://localhost:8000)

---

## Funcionalidades

- Cadastro, edição e variação de produtos e estoque.
- Carrinho de compras com controle de estoque e cálculo automático de frete.
- Aplicação e remoção de cupons de desconto.
- Consulta de endereço por CEP (ViaCEP).
- Envio de e-mail ao finalizar ou cancelar pedido.
- Webhook para atualização/cancelamento de pedidos.

---

## Exemplo de Seeder de Cupons

Veja o arquivo `database/seeders/CupomSeeder.php` para exemplos de cupons gerados automaticamente. Exemplo de cupom:

```php
Cupom::create([
    'codigo' => 'DESCONTO10',
    'desconto_valor' => 10.00,
    'desconto_percentual' => null,
    'valor_minimo' => 50.00,
    'data_validade' => '2024-12-31',
]);
```

