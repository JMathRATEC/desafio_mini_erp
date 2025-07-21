<x-mail::message>
# Pedido Cancelado (#{{ $pedido->id }})

Olá,

Lamentamos informar que o seu pedido **#{{ $pedido->id }}** foi cancelado.

Se você não solicitou este cancelamento ou se tiver alguma dúvida, por favor, entre em contato com nosso suporte.

O estorno dos valores, se aplicável, será processado em breve.

Atenciosamente,<br>
{{ config('app.name') }}
</x-mail::message>
