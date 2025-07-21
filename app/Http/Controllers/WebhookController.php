<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Estoque;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WebhookController extends Controller
{
    public function handle(Request $r)
    {
        $data = $r->validate([
            'id' => 'required|integer|exists:pedidos,id',
            'status' => ['required', 'string', Rule::in(['cancelado', 'pago', 'enviado', 'entregue'])],
        ]);

        $pedido = Pedido::find($data['id']);

        if ($data['status'] === 'cancelado') {
            DB::transaction(function () use ($pedido) {
                foreach ($pedido->itens as $item) {
                    if (isset($item['estoque_id']) && isset($item['quantidade'])) {
                        Estoque::find($item['estoque_id'])
                            ->increment('quantidade', $item['quantidade']);
                    }
                }
                $pedido->delete();
            });
            
            return response()->json(['message' => 'Pedido cancelado e estoque estornado.']);
        } 
        
        $pedido->update(['status' => $data['status']]);
        
        return response()->json(['message' => 'Status do pedido atualizado.']);
    }
}
