<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\Cupom;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Estoque;


class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::orderBy('created_at', 'desc')->get();

        return view('pedidos.index', compact('pedidos'));
    }

    public function adicionarAoCarrinho(Request $r, $id)
    {
        $produto = \App\Models\Produto::findOrFail($id);
        $estoqueId = $r->estoque_id;
        $estoque = $produto->estoques()->where('id', $estoqueId)->where('quantidade', '>', 0)->firstOrFail();
        $estoque->decrement('quantidade');

        $cart = session('cart', []);
        $key = $produto->id . '_' . $estoque->id;
        $cart[$key] = [
            'produto_id' => $produto->id,
            'estoque_id' => $estoque->id,
            'nome' => $produto->nome . ($estoque->variacao ? " ({$estoque->variacao})" : ''),
            'quantidade' => ($cart[$key]['quantidade'] ?? 0) + 1,
            'preco' => $produto->preco
        ];
        session(['cart' => $cart]);

        return back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function mostrarCarrinho()
    {
        return view('carrinho');
    }

    public function aplicarCupom(Request $r)
    {
        $r->validate(['codigo' => 'required']);
        $cupom = Cupom::where('codigo', $r->codigo)->first();
        $subtotal = $r->subtotal;
        if (!$cupom || $cupom->data_validade < now() || $subtotal < $cupom->valor_minimo) {
            return back()->with('error', 'Cupom inválido');
        }
        $desconto = $cupom->desconto_valor
            ?? $subtotal * $cupom->desconto_percentual / 100;
        session(['cupom' => ['codigo' => $cupom->codigo, 'desconto' => $desconto]]);
        return back();
    }

    public function removerCupom(Request $r)
    {
        session()->forget('cupom');
        return back()->with('success', 'Cupom removido.');
    }

    public function consultaCep($cep)
    {
        $data = json_decode(file_get_contents("https://viacep.com.br/ws/{$cep}/json/"), true);
        return response()->json($data);
    }

    public function finalizar(Request $r)
    {
        $r->validate([
            'cep' => 'required',
            'logradouro' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'email' => 'required|email'
        ]);
        $cart = session('cart', []);
        $subtotal = $r->subtotal;
        // Cálculo do frete conforme regras
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15.00;
        } elseif ($subtotal > 200) {
            $frete = 0.00;
        } else {
            $frete = 20.00;
        }
        $cupom = session('cupom.desconto', 0);
        $total = $subtotal + $frete - $cupom;

        $pedido = Pedido::create([
            'itens' => $cart,
            'subtotal' => $subtotal,
            'frete' => $frete,
            'total' => $total,
            'email' => $r->email,
            'cep' => $r->cep,
            'logradouro' => $r->logradouro,
            'bairro' => $r->bairro,
            'cidade' => $r->cidade,
            'estado' => $r->estado
        ]);

        Mail::to($r->email)->send(new \App\Mail\PedidoRealizado($pedido));

        session()->forget(['cart', 'cupom']);
        return redirect()->route('produtos.index')->with('success', 'Pedido concluído. Você receberá um e-mail de confirmação.');
    }

    public function destroy(Pedido $pedido)
    {
        DB::transaction(function () use ($pedido) {
            foreach ($pedido->itens as $item) {
                if (isset($item['estoque_id']) && isset($item['quantidade'])) {
                    Estoque::find($item['estoque_id'])->increment('quantidade', $item['quantidade']);
                }
            }
            
            if ($pedido->email) {
                Mail::to($pedido->email)->send(new \App\Mail\PedidoCancelado($pedido));
            }
            
            $pedido->delete();
        });

        return back()->with('success', "Pedido #{$pedido->id} cancelado com sucesso.");
    }
}
