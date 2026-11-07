<?php

namespace App\Http\Controllers;

use App\Models\Saida;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        // Pega o mês/ano informado no filtro e ou coloca o mes atual como padrao
        $mesFiltro = $request->get('mes', date('Y-m'));

        // Separa Ano e Mês
        $partesData = explode('-', $mesFiltro);
        $ano = $partesData[0] ?? date('Y');
        $mes = $partesData[1] ?? date('m');

        // Busca as saídas do mês selecionado trazendo o relacionamento de produtos
        $relatorios = Saida::with('produto')
            ->whereYear('data_saida', $ano)
            ->whereMonth('data_saida', $mes)
            ->orderBy('data_saida', 'desc')
            ->paginate(10);

        // Envia a variável $relatorios exata para a view
        return view('relatorios.index', compact('relatorios'));
    }
}