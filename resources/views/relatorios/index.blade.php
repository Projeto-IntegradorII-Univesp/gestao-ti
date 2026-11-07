@extends('layouts.app')

@section('titulo', 'Relatórios')

@section('conteudo')

<!-- CABEÇALHO, FILTRO DE MÊS E BOTÃO IMPRIMIR -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <h3 class="text-emerald fw-bold m-0">
        <i class="fa-solid fa-file-invoice me-2"></i> Relatório de Movimentação
    </h3>
    
    <div class="d-flex align-items-center gap-2 flex-wrap d-print-none">
        <form action="{{ route('relatorios.index') }}" method="GET" class="d-flex gap-2">
            <input type="month" name="mes" class="form-control" value="{{ request('mes', date('Y-m')) }}">
            <button type="submit" class="btn btn-emerald fw-semibold">Filtrar</button>
        </form>

        <button type="button" class="btn btn-outline-emerald fw-bold d-flex align-items-center gap-1 shadow-sm" onclick="window.print()">
            <i class="fa-solid fa-print"></i>
            <span>Imprimir Relatório</span>
        </button>
    </div>
</div>

<!-- CABEÇALHO EXCLUSIVO PARA IMPRESSÃO DA FOLHA A4 -->
<div class="d-none d-print-block mb-4 text-center border-bottom pb-3">
    <h2 class="fw-bold text-dark m-0">Relatório de Saídas / Movimentação do Almoxarifado</h2>
    <p class="text-muted m-0">Período Selecionado: <strong>{{ \Carbon\Carbon::parse(request('mes', date('Y-m')) . '-01')->format('m/Y') }}</strong></p>
    <p class="small text-muted m-0">Gerado em: {{ date('d/m/Y H:i') }}</p>
</div>

<!-- TABELA DE RELATÓRIO -->
<div class="card border-0 shadow-sm border-print-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="max-width: 350px;">Produto</th>
                        <th>Setor Destino</th>
                        <th class="text-center">Qtd.</th>
                        <th>Responsável</th>
                        <th class="text-end pe-3">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($relatorios as $r)
                    <tr>
                        <td class="ps-3 fw-bold text-dark">
                            <!-- Mobile / Tela -->
                            <span class="d-inline d-md-none text-truncate" style="max-width: 140px;">
                                {{ Str::words($r->produto->nome ?? 'Removido', 2, '...') }}
                            </span>
                            <!-- PC / Impressão -->
                            <span class="d-none d-md-inline">
                                {{ $r->produto->nome ?? 'Produto Removido' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $r->destino_setor }}</span>
                        </td>
                        <td class="text-center"><span class="badge bg-secondary">{{ $r->quantidade }}</span></td>
                        <td class="small text-muted">{{ $r->responsavel ?? '-' }}</td>
                        <td class="text-end pe-3 small text-muted">{{ \Carbon\Carbon::parse($r->data_saida)->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Nenhum registro encontrado para o período.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($relatorios, 'hasPages') && $relatorios->hasPages())
    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center d-print-none">
        {{ $relatorios->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- CSS DEDICADO PARA FORMATAR A IMPRESSÃO -->
<style>
@media print {
    /* Oculta menus, navbar, rodapés e botões */
    .navbar, .d-print-none, .btn, form, footer {
        display: none !important;
    }

    body {
        background-color: #fff !important;
        padding: 0 !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .table th, .table td {
        border: 1px solid #dee2e6 !important;
        padding: 8px !important;
    }

    .badge {
        border: none !important;
        color: #000 !important;
        background: transparent !important;
        padding: 0 !important;
    }
}
</style>

@endsection