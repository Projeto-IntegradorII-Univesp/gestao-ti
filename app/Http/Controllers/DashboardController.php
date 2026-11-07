<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Saida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        //parte do estoque avisando quando tiver menor que o minimo.
        $totalProdutos = Produto::count();
        $totalEstoque = Produto::sum('estoque_atual');
        $itensAlerta = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')->count();
        
        // Alertas e Produtos Críticos
        $alertaEstoque = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')->get();
        $produtosCriticos = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')
            ->orderBy('estoque_atual', 'asc')
            ->take(5)
            ->get();

        // Saídas e Movimentações
        $totalSaidas = Saida::sum('quantidade');
        $totalSaidasMes = Saida::whereYear('data_saida', date('Y'))
            ->whereMonth('data_saida', date('m'))
            ->sum('quantidade');

        // Listas dos produtos
        $produtosRecentes = Produto::orderBy('id', 'desc')->take(5)->get();
        $ultimasSaidas = Saida::with('produto')->orderBy('id', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalProdutos', 
            'totalEstoque', 
            'itensAlerta', 
            'alertaEstoque', 
            'produtosCriticos',
            'totalSaidas',
            'totalSaidasMes', 
            'produtosRecentes',
            'ultimasSaidas'
        ));
    }

    public function fluxoSaidas()
    {
        $fluxo = Saida::select(
                DB::raw("DATE_FORMAT(data_saida, '%m/%Y') as mes"),
                DB::raw("SUM(quantidade) as total")
            )
            ->groupBy('mes')
            ->orderBy('data_saida', 'asc')
            ->take(6)
            ->get();

        return response()->json($fluxo);
    }
}