<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Estoque;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::with('estoques')->get();
        return view('produtos.index', compact('produtos'));
    }

    public function show(Produto $produto)
    {
        return response()->json($produto->load('estoques'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'nome' => 'required',
            'preco' => 'required|numeric|min:0'
        ]);
        $p = Produto::create($r->only('nome', 'preco'));
        if ($r->filled('variacoes')) {
            foreach ($r->variacoes as $i => $v) {
                Estoque::create([
                    'produto_id' => $p->id,
                    'variacao' => $v,
                    'quantidade' => $r->quantidades[$i] ?? 0
                ]);
            }
        } else {
            Estoque::create([
                'produto_id' => $p->id,
                'quantidade' => $r->quantidade ?? 0
            ]);
        }
        return back()->with('success', 'Produto criado.');
    }

    public function update(Request $r, Produto $produto)
    {
        $r->validate([
            'nome' => 'required',
            'preco' => 'required|numeric|min:0',
            'variacoes.*.variacao' => 'nullable|string',
            'variacoes.*.quantidade' => 'required|integer|min:0',
        ]);

        $produto->update($r->only('nome', 'preco'));

        $idsVariacoesEnviadas = [];
        if ($r->has('variacoes')) {
            foreach ($r->variacoes as $id => $variacaoData) {
                $estoque = Estoque::updateOrCreate(
                    ['id' => $id, 'produto_id' => $produto->id],
                    ['variacao' => $variacaoData['variacao'], 'quantidade' => $variacaoData['quantidade']]
                );
                $idsVariacoesEnviadas[] = $estoque->id;
            }
        }

        // Remove variações que não foram enviadas
        $produto->estoques()->whereNotIn('id', $idsVariacoesEnviadas)->delete();

        return back()->with('success', 'Produto atualizado.');
    }
}
