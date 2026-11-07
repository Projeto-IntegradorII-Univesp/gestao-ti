<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->get('busca');

        $produtos = Produto::when($busca, function ($query) use ($busca) {
                $query->where('nome', 'like', "%{$busca}%")
                      ->orWhere('categoria', 'like', "%{$busca}%");
            })
            ->orderBy('nome', 'asc')
            ->paginate(10);

        return view('produtos.index', compact('produtos', 'busca'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'estoque_atual' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $nomeImagem = 'padrao.png';
        if ($request->hasFile('imagem')) {
            $caminho = $request->file('imagem')->store('produtos', 'public');
            $nomeImagem = basename($caminho);
        }

        Produto::create([
            'nome' => $request->nome,
            'categoria' => $request->categoria,
            'estoque_atual' => $request->estoque_atual,
            'estoque_minimo' => $request->estoque_minimo,
            'imagem' => $nomeImagem,
            'ativo' => 1,
            'data_cadastro' => now()
        ]);

        return back()->with('sucesso', 'Produto cadastrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'estoque_atual' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($request->hasFile('imagem')) {
            if ($produto->imagem && $produto->imagem !== 'padrao.png' && Storage::disk('public')->exists('produtos/' . $produto->imagem)) {
                Storage::disk('public')->delete('produtos/' . $produto->imagem);
            }
            $caminho = $request->file('imagem')->store('produtos', 'public');
            $produto->imagem = basename($caminho);
        }

        $produto->nome = $request->nome;
        $produto->categoria = $request->categoria;
        $produto->estoque_atual = $request->estoque_atual;
        $produto->estoque_minimo = $request->estoque_minimo;
        $produto->save();

        return back()->with('sucesso', 'Insumo atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);

        if ($produto->imagem && $produto->imagem !== 'padrao.png' && Storage::disk('public')->exists('produtos/' . $produto->imagem)) {
            Storage::disk('public')->delete('produtos/' . $produto->imagem);
        }

        $produto->delete();

        return back()->with('sucesso', 'Insumo removido com sucesso!');
    }
}