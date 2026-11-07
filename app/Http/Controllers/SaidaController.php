<?php

namespace App\Http\Controllers;

use App\Models\Saida;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaidaController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->get('busca');

        $saidas = Saida::with('produto')
            ->when($busca, function ($query) use ($busca) {
                $query->whereHas('produto', function ($q) use ($busca) {
                    $q->where('nome', 'like', "%{$busca}%");
                })->orWhere('destino_setor', 'like', "%{$busca}%")
                  ->orWhere('responsavel', 'like', "%{$busca}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('saidas.index', compact('saidas', 'busca'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
            'destino_setor' => 'required|string|max:200',
            'responsavel' => 'nullable|string|max:100',
            'observacao' => 'nullable|string'
        ]);

        $produto = Produto::findOrFail($request->produto_id);

        if ($produto->estoque_atual < $request->quantidade) {
            return back()->with('erro', 'Quantidade solicitada maior que o estoque disponível!');
        }

        DB::transaction(function () use ($request, $produto) {
            // Abater do estoque no MySQL
            $produto->decrement('estoque_atual', $request->quantidade);

            // Registrar saída no MySQL
            Saida::create([
                'produto_id' => $request->produto_id,
                'quantidade' => $request->quantidade,
                'destino_setor' => $request->destino_setor,
                'responsavel' => $request->responsavel,
                'observacao' => $request->observacao,
                'data_saida' => now()
            ]);
        });

        return back()->with('sucesso', 'Saída registrada com sucesso!');
    }

    public function comprovante($id)
    {
        $saida = Saida::with('produto')->findOrFail($id);
        return view('saidas.comprovante', compact('saida'));
    }
}