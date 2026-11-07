@extends('layouts.app')

@section('titulo', 'Saídas e Baixas')

@section('conteudo')

<!-- CABEÇALHO E CAMPO DE BUSCA EM TEMPO REAL -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 d-print-none">
    <h3 class="text-emerald fw-bold m-0">
        <i class="fa-solid fa-truck-ramp-box me-2"></i> Saídas / Baixas
    </h3>

    <!-- Campo de Busca em Tempo Real -->
    <div class="input-group" style="max-width: 350px; width: 100%;">
        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
        <input type="text" id="inputBuscaSaida" class="form-control border-start-0" placeholder="Digite para buscar produto ou setor..." autocomplete="off">
    </div>
</div>

<!-- TABELA DE SAÍDAS -->
<div class="card border-0 shadow-sm d-print-none">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0" id="tabelaSaidas">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="max-width: 350px;">Produto</th>
                        <th>Destino / Setor</th>
                        <th class="text-center">Qtd.</th>
                        <th class="d-none d-sm-table-cell">Data</th>
                        <th class="text-center" style="width: 60px;">Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($saidas as $s)
                    <tr class="linha-item">
                        <td class="ps-3 fw-bold text-dark" title="{{ $s->produto->nome ?? 'Produto Removido' }}">
                            <!-- Mobile -->
                            <span class="d-inline d-md-none text-truncate" style="max-width: 140px;">
                                {{ Str::words($s->produto->nome ?? 'N/A', 2, '...') }}
                            </span>
                            <!-- PC -->
                            <span class="d-none d-md-inline">
                                {{ Str::words($s->produto->nome ?? 'N/A', 8, '...') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $s->destino_setor }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary fs-6">{{ $s->quantidade }}</span>
                        </td>
                        <td class="small text-muted d-none d-sm-table-cell">
                            {{ \Carbon\Carbon::parse($s->data_saida)->format('d/m/Y') }}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-emerald py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalDetalhesSaida{{ $s->id }}" title="Visualizar Detalhes">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-magnifying-glass fa-2xl mb-2 d-block"></i>
                            Nenhuma saída encontrada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($saidas->hasPages())
    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
        {{ $saidas->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- MODAIS DE DETALHES -->
@foreach($saidas as $s)
<div class="modal fade" id="modalDetalhesSaida{{ $s->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" id="areaImpressaoSaida{{ $s->id }}">
            <div class="modal-header bg-emerald text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-file-contract me-2"></i> Comprovante de Baixa #{{ $s->id }}
                </h5>
                <button type="button" class="btn-close btn-close-white d-print-none" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="text-muted small fw-bold text-uppercase d-block">Produto</label>
                    <span class="fs-5 fw-bold text-dark">{{ $s->produto->nome ?? 'N/A' }}</span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="text-muted small fw-bold text-uppercase d-block">Setor Destino</label>
                        <span class="fw-semibold text-dark">{{ $s->destino_setor }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-bold text-uppercase d-block">Quantidade Retirada</label>
                        <span class="badge bg-emerald fs-6">{{ $s->quantidade }} un</span>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="text-muted small fw-bold text-uppercase d-block">Responsável</label>
                        <span class="fw-semibold text-dark">{{ $s->responsavel ?? 'Não informado' }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-bold text-uppercase d-block">Data e Hora</label>
                        <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($s->data_saida)->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div>
                    <label class="text-muted small fw-bold text-uppercase d-block">Observação / Justificativa</label>
                    <div class="p-3 bg-light rounded border text-secondary">
                        {{ $s->observacao ?: 'Nenhuma observação registrada.' }}
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-center d-none d-print-block">
                    <p class="small text-muted mb-0">Gestão de TI - Sistema de Almoxarifado</p>
                </div>
            </div>
            <div class="modal-footer bg-light d-print-none">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-emerald fw-bold" onclick="imprimirComprovante('modalDetalhesSaida{{ $s->id }}')">
                    <i class="fa-solid fa-print me-1"></i> Imprimir Recibo
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- SCRIPT DE BUSCA EM TEMPO REAL E IMPRESSÃO -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputBusca = document.getElementById('inputBuscaSaida');
    const linhas = document.querySelectorAll('#tabelaSaidas tbody .linha-item');

    if (inputBusca) {
        inputBusca.addEventListener('input', function () {
            const termo = this.value.toLowerCase().trim();

            linhas.forEach(linha => {
                const textoLinha = linha.textContent.toLowerCase();
                if (textoLinha.includes(termo)) {
                    linha.style.display = '';
                } else {
                    linha.style.display = 'none';
                }
            });
        });
    }
});

function imprimirComprovante(modalId) {
    const conteudoModal = document.getElementById(modalId).querySelector('.modal-content').innerHTML;
    const janelaImpressao = window.open('', '', 'height=600,width=800');
    
    janelaImpressao.document.write('<html><head><title>Comprovante de Saída</title>');
    janelaImpressao.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    janelaImpressao.document.write('<style>.d-print-none { display: none !important; }</style>');
    janelaImpressao.document.write('</head><body class="p-4">');
    janelaImpressao.document.write(conteudoModal);
    janelaImpressao.document.write('</body></html>');
    
    janelaImpressao.document.close();
    janelaImpressao.focus();
    
    setTimeout(function() {
        janelaImpressao.print();
        janelaImpressao.close();
    }, 500);
}
</script>
@endpush

@endsection