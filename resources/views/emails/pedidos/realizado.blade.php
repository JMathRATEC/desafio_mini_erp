@component('mail::message')
# Pedido Recebido (#{{ $pedido->id }})
Obrigado por comprar conosco! Abaixo estão os detalhes do seu pedido:

@component('mail::table')
| Produto         | Quantidade | Preço Unitário | Total       |
| --------------- | :--------: | -------------: | ----------: |
@foreach($pedido->itens as $item)
| {{ $item['nome'] }} | {{ $item['quantidade'] }}    | R$ {{ number_format($item['preco'],2,',','.') }}      | R$ {{ number_format($item['quantidade']*$item['preco'],2,',','.') }} |
@endforeach
| **Subtotal**    |            |                | R$ {{ number_format($pedido->subtotal,2,',','.') }} |
| **Frete**       |            |                | R$ {{ number_format($pedido->frete,2,',','.') }}    |
| **Total**       |            |                | R$ {{ number_format($pedido->total,2,',','.') }}    |
@endcomponent

**Endereço de Entrega**  
CEP: {{ $pedido->cep }}  
{{ $pedido->logradouro }}, {{ $pedido->bairro }} – {{ $pedido->cidade }}/{{ $pedido->estado }}

Obrigado por escolher a gente!  
Atenciosamente,  
Equipe Mini ERP
@endcomponent