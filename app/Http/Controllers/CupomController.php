<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cupom;

class CupomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cupons = Cupom::orderBy('data_validade')->get();
        return view('cupons.index', compact('cupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo'                => 'required|string|unique:cupons,codigo',
            'desconto_valor'        => 'nullable|numeric|min:0|required_without:desconto_percentual',
            'desconto_percentual'   => 'nullable|numeric|min:0|max:100|required_without:desconto_valor',
            'valor_minimo'          => 'required|numeric|min:0',
            'data_validade'         => 'required|date|after_or_equal:today',
        ]);

        Cupom::create($data);

        return redirect()
            ->route('cupons.index')
            ->with('success', 'Cupom criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cupom = Cupom::findOrFail($id);
        return view('cupons.show', compact('cupom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cupom = Cupom::findOrFail($id);
        return view('cupons.edit', compact('cupom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cupom = Cupom::findOrFail($id);

        $data = $request->validate([
            'codigo'                => "required|string|unique:cupons,codigo,$id",
            'desconto_valor'        => 'nullable|numeric|min:0|required_without:desconto_percentual',
            'desconto_percentual'   => 'nullable|numeric|min:0|max:100|required_without:desconto_valor',
            'valor_minimo'          => 'required|numeric|min:0',
            'data_validade'         => 'required|date|after_or_equal:today',
        ]);

        $cupom->update($data);

        return redirect()
            ->route('cupons.index')
            ->with('success', 'Cupom atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cupom = Cupom::findOrFail($id);
        $cupom->delete();

        return redirect()
            ->route('cupons.index')
            ->with('success', 'Cupom removido com sucesso.');
    }
}
