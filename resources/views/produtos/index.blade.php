@extends('layouts.app')

@section('titulo', 'Almoxarifado')

@section('conteudo')

{{-- Alertas de Sucesso ou Erro --}}
@if(session('sucesso'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('sucesso') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('erro'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('erro') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- CABEÇALHO COM TÍTULO, BARRA DE BUSCA E BOTÃO NOVO -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <h3 class="text-emerald fw-bold m-0"><i class="fa-solid fa-box-archive me-2"></i> Equipamentos</h3>
    
    <div class="d-flex align-items-center gap-2" style="max-width: 450px; width: 100%;">
        <!-- Campo de Busca em Tempo Real -->
        <div class="input-group flex-grow-1">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="text" id="inputBuscaProduto" class="form-control border-start-0" placeholder="Digite para buscar insumo..." autocomplete="off">
        </div>

        @if(Auth::user()->tipo === 'admin')
        <!-- Botão Novo -->
        <button class="btn btn-emerald fw-bold text-nowrap d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovoProduto">
            <i class="fa-solid fa-plus"></i>
            <span>Novo</span>
        </button>
        @endif
    </div>
</div>

<!-- TABELA DE PRODUTOS -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0" id="tabelaProdutos">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 60px;">Foto</th>
                        <th style="max-width: 300px;">Insumo</th>
                        <th class="d-none d-md-table-cell">Categoria</th>
                        <th class="text-center">Qtd.</th>
                        <th class="text-center d-none d-sm-table-cell">Mín.</th>
                        <th class="text-center" style="width: 170px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produtos as $p)
                    <tr class="linha-item">
                        <!-- Miniatura da Foto -->
                        <td class="ps-3">
                            @if($p->imagem && Illuminate\Support\Facades\Storage::disk('public')->exists('produtos/' . $p->imagem))
                                <img src="{{ Illuminate\Support\Facades\Storage::url('produtos/' . $p->imagem) }}" class="rounded border" style="width: 36px; height: 36px; object-fit: cover;">
                            @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted" style="width: 36px; height: 36px;">
                                    <i class="fa-solid fa-box-open small"></i>
                                </div>
                            @endif
                        </td>

                        <!-- Nome do Produto -->
                        <td class="fw-bold text-dark coluna-busca" title="{{ $p->nome }}">
                            <span class="d-inline d-md-none text-truncate" style="max-width: 130px;">
                                {{ Str::words($p->nome, 2, '...') }}
                            </span>
                            <span class="d-none d-md-inline">
                                {{ Str::words($p->nome, 8, '...') }}
                            </span>
                        </td>

                        <td class="d-none d-md-table-cell coluna-busca">
                            <span class="badge bg-light text-dark border">{{ $p->categoria }}</span>
                        </td>

                        <td class="text-center">
                            @if($p->estoque_atual <= $p->estoque_minimo)
                                <span class="badge bg-danger">{{ $p->estoque_atual }} un</span>
                            @else
                                <span class="badge bg-success">{{ $p->estoque_atual }} un</span>
                            @endif
                        </td>

                        <td class="text-center text-muted small d-none d-sm-table-cell">{{ $p->estoque_minimo }} un</td>

                        <!-- Ações -->
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <!-- Botão Ver Detalhes -->
                                <button class="btn btn-sm btn-outline-emerald py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalVerProd{{ $p->id }}" title="Ver Detalhes">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                
                                <!-- Botão Dar Baixa -->
                                <button class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalBaixaProd{{ $p->id }}" title="Registrar Baixa/Saída" {{ $p->estoque_atual <= 0 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-boxes-packing"></i>
                                </button>

                                @if(Auth::user()->tipo === 'admin')
                                <!-- Botão Editar -->
                                <button class="btn btn-sm btn-outline-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditarProd{{ $p->id }}" title="Editar Insumo">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <!-- Botão Excluir -->
                                <button class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalExcluirProd{{ $p->id }}" title="Excluir Insumo">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Nenhum produto cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($produtos, 'hasPages') && $produtos->hasPages())
    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
        {{ $produtos->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- MODAIS DO SISTEMA -->
@foreach($produtos as $p)
    <!-- 1. MODAL VER DETALHES -->
    <div class="modal fade" id="modalVerProd{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-emerald text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-box me-2"></i> Detalhes do Insumo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        @if($p->imagem && $p->imagem !== 'padrao.png' && Illuminate\Support\Facades\Storage::disk('public')->exists('produtos/' . $p->imagem))
                            <img src="{{ Illuminate\Support\Facades\Storage::url('produtos/' . $p->imagem) }}" class="img-fluid rounded border shadow-sm" style="max-height: 180px; object-fit: cover;">
                        @else
                            <div class="p-4 bg-light rounded border text-muted d-inline-block">
                                <i class="fa-solid fa-image fa-3x mb-2 d-block text-secondary"></i>
                                <span>Sem imagem cadastrada</span>
                            </div>
                        @endif
                    </div>

                    <h4 class="fw-bold text-dark mb-3">{{ $p->nome }}</h4>

                    <div class="row text-start g-3 bg-light p-3 rounded border">
                        <div class="col-6">
                            <small class="text-muted fw-bold d-block text-uppercase">Categoria</small>
                            <span class="fw-semibold text-dark">{{ $p->categoria }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted fw-bold d-block text-uppercase">Estoque Atual</small>
                            <span class="badge bg-emerald fs-6">{{ $p->estoque_atual }} un</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted fw-bold d-block text-uppercase">Estoque Mínimo</small>
                            <span class="fw-semibold text-dark">{{ $p->estoque_minimo }} un</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted fw-bold d-block text-uppercase">Data Cadastro</small>
                            <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($p->data_cadastro ?? $p->created_at)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MODAL REGISTRAR BAIXA / SAÍDA -->
    <div class="modal fade" id="modalBaixaProd{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('saidas.store') }}" method="POST" class="modal-content shadow-lg border-0">
                @csrf
                <input type="hidden" name="produto_id" value="{{ $p->id }}">
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-boxes-packing me-2"></i> Registrar Saída de Insumo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <small class="text-muted d-block fw-bold">INSUMO SELECIONADO</small>
                            <span class="fw-bold text-dark fs-6">{{ $p->nome }}</span>
                        </div>
                        <span class="badge bg-emerald fs-6">{{ $p->estoque_atual }} un em estoque</span>
                    </div>

                    <!-- CAMPO 1: QUANTIDADE -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Quantidade de Saída <span class="text-danger">*</span></label>
                        <input type="number" name="quantidade" class="form-control form-control-lg" required min="1" max="{{ $p->estoque_atual }}" placeholder="Ex: 1">
                    </div>

                    <!-- CAMPO 2: DESTINO / SETOR (Mapeado exato com o MySQL) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Destino / Setor <span class="text-danger">*</span></label>
                        <input type="text" name="destino_setor" class="form-control" required placeholder="Ex: Secretaria de Administração">
                    </div>

                    <!-- CAMPO 3: RESPONSÁVEL -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Responsável pelo Recebimento</label>
                        <input type="text" name="responsavel" class="form-control" placeholder="Ex: João Silva">
                    </div>

                    <!-- CAMPO 4: OBSERVAÇÃO -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Observação / Motivo</label>
                        <textarea name="observacao" class="form-control" rows="2" placeholder="Ex: Substituição de teclado queimado"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fa-solid fa-check me-1"></i> Confirmar Baixa</button>
                </div>
            </form>
        </div>
    </div>

    @if(Auth::user()->tipo === 'admin')
    <!-- 3. MODAL EDITAR -->
    <div class="modal fade" id="modalEditarProd{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('produtos.update', $p->id) }}" method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen me-2"></i> Editar Insumo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 text-center">
                        @if($p->imagem && $p->imagem !== 'padrao.png' && Illuminate\Support\Facades\Storage::disk('public')->exists('produtos/' . $p->imagem))
                            <img src="{{ Illuminate\Support\Facades\Storage::url('produtos/' . $p->imagem) }}" class="rounded border mb-2" style="width: 70px; height: 70px; object-fit: cover;">
                        @endif
                        <div class="text-start">
                            <label class="form-label fw-bold">Alterar Foto (Opcional)</label>
                            <input type="file" name="imagem" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome do Insumo</label>
                        <input type="text" name="nome" class="form-control" required value="{{ $p->nome }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Categoria</label>
                        <input type="text" name="categoria" class="form-control" required value="{{ $p->categoria }}">
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Estoque Atual</label>
                            <input type="number" name="estoque_atual" class="form-control" required min="0" value="{{ $p->estoque_atual }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Estoque Mínimo</label>
                            <input type="number" name="estoque_minimo" class="form-control" required min="0" value="{{ $p->estoque_minimo }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark"><i class="fa-solid fa-floppy-disk me-1"></i> Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. MODAL EXCLUIR -->
    <div class="modal fade" id="modalExcluirProd{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i> Excluir Insumo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="fs-5 mb-1">Deseja remover do estoque:</p>
                    <p class="fw-bold text-danger fs-5">{{ $p->nome }}</p>
                    <p class="small text-muted mb-0">Esta ação não poderá ser desfeita.</p>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('produtos.destroy', $p->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger fw-bold">Sim, Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

<!-- MODAL CADASTRAR NOVO INSUMO -->
@if(Auth::user()->tipo === 'admin')
<div class="modal fade" id="modalNovoProduto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0">
            @csrf
            <div class="modal-header bg-emerald text-white">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus me-2"></i> Cadastrar Novo Insumo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Foto do Insumo (Opcional)</label>
                    <input type="file" name="imagem" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nome do Insumo</label>
                    <input type="text" name="nome" class="form-control" required placeholder="Ex: Teclado USB Dell KB216">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Categoria</label>
                    <input type="text" name="categoria" class="form-control" required placeholder="Ex: Periféricos">
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Estoque Inicial</label>
                        <input type="number" name="estoque_atual" class="form-control" required min="0" value="1">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Estoque Mínimo</label>
                        <input type="number" name="estoque_minimo" class="form-control" required min="0" value="2">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-emerald fw-bold"><i class="fa-solid fa-floppy-disk me-1"></i> Cadastrar</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- SCRIPT DE BUSCA EM TEMPO REAL -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputBusca = document.getElementById('inputBuscaProduto');
    const linhas = document.querySelectorAll('#tabelaProdutos tbody .linha-item');

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
</script>
@endpush

@endsection