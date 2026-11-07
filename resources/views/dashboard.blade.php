@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-emerald fw-bold m-0"><i class="fa-solid fa-chart-line me-2"></i> Painel de Controle</h3>
</div>

<!-- CARDS DE MÉTRICAS -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-emerald border-4 h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small" style="font-size: 0.75rem;">Total Produtos</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalProdutos }}</h3>
                    </div>
                    <div class="bg-emerald-light p-2 p-md-3 rounded-circle text-emerald d-none d-sm-block">
                        <i class="fa-solid fa-boxes-stacked fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4 h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small" style="font-size: 0.75rem;">Qtd. em Estoque</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalEstoque }}</h3>
                    </div>
                    <div class="bg-primary-subtle p-2 p-md-3 rounded-circle text-primary d-none d-sm-block">
                        <i class="fa-solid fa-cubes fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-warning border-4 h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small" style="font-size: 0.75rem;">Estoque Crítico</span>
                        <h3 class="fw-bold text-warning mt-1 mb-0">{{ $itensAlerta }}</h3>
                    </div>
                    <div class="bg-warning-subtle p-2 p-md-3 rounded-circle text-warning d-none d-sm-block">
                        <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-danger border-4 h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small" style="font-size: 0.75rem;">Saídas no Mês</span>
                        <h3 class="fw-bold text-danger mt-1 mb-0">{{ $totalSaidasMes }}</h3>
                    </div>
                    <div class="bg-danger-subtle p-2 p-md-3 rounded-circle text-danger d-none d-sm-block">
                        <i class="fa-solid fa-truck-ramp-box fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABELAS LADO A LADO -->
<div class="row g-4 mb-4">
    <!-- PAINEL DE ALERTAS CRÍTICOS -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0 text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Estoque Baixo</h6>
                <span class="badge bg-danger rounded-pill">{{ count($alertaEstoque) }} itens</span>
            </div>
            <div class="card-body p-0">
                @if(count($alertaEstoque) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="max-width: 150px;">Produto</th>
                                    <th class="d-none d-sm-table-cell">Categoria</th>
                                    <th class="text-center">Atual</th>
                                    <th class="text-center">Mín.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alertaEstoque as $item)
                                <tr>
                                    <td class="ps-3 fw-bold text-dark text-truncate" style="max-width: 140px;" title="{{ $item->nome }}">
                                        {{ Str::words($item->nome, 2, '...') }}
                                    </td>
                                    <td class="d-none d-sm-table-cell"><span class="badge bg-light text-dark border">{{ $item->categoria }}</span></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $item->estoque_atual }} un</span>
                                    </td>
                                    <td class="text-center text-muted fw-semibold small">{{ $item->estoque_minimo }} un</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fa-solid fa-circle-check fa-2xl text-success mb-2 d-block"></i>
                        Estoque normalizado!
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ÚLTIMAS SAÍDAS DE MATERIAIS -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0 text-dark"><i class="fa-solid fa-clock-rotate-left me-2"></i> Últimas Baixas</h6>
                <a href="{{ route('saidas.index') }}" class="btn btn-sm btn-outline-emerald fw-semibold">Ver Todas</a>
            </div>
            <div class="card-body p-0">
                @if(count($ultimasSaidas) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="max-width: 150px;">Produto</th>
                                    <th>Setor</th>
                                    <th class="text-center">Qtd.</th>
                                    <th class="text-end pe-3 d-none d-sm-table-cell">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasSaidas as $saida)
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark text-truncate" style="max-width: 140px;" title="{{ $saida->produto->nome ?? 'N/A' }}">
                                        {{ Str::words($saida->produto->nome ?? 'Removido', 2, '...') }}
                                    </td>
                                    <td class="small text-muted text-truncate" style="max-width: 110px;">{{ $saida->destino_setor }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $saida->quantidade }}</span>
                                    </td>
                                    <td class="text-end pe-3 small text-muted d-none d-sm-table-cell">
                                        {{ \Carbon\Carbon::parse($saida->data_saida)->format('d/m/Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        Nenhuma saída recente.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection