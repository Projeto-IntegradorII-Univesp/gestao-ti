@extends('layouts.app')

@section('titulo', 'Gestão de Usuários')

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

<!-- CABEÇALHO COM TÍTULOS -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <h3 class="text-emerald fw-bold m-0"><i class="fa-solid fa-users-gear me-2"></i> Gestão de Usuários</h3>
    
    <div class="d-flex align-items-center gap-2" style="max-width: 450px; width: 100%;">
        <!-- Campo de Busca em Tempo Real -->
        <div class="input-group flex-grow-1">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="text" id="inputBuscaUsuario" class="form-control border-start-0" placeholder="Digite para buscar usuário..." autocomplete="off">
        </div>

        @if(Auth::user()->tipo === 'admin' || Auth::user()->nivel_acesso === 'admin')
        <button class="btn btn-emerald fw-bold text-nowrap d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovoUsuario">
            <i class="fa-solid fa-user-plus"></i>
            <span>Novo</span>
        </button>
        @endif
    </div>
</div>

<!-- TABELA DE USUÁRIOS -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0" id="tabelaUsuarios">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">Foto</th>
                        <th style="max-width: 140px;">Nome</th>
                        <th class="d-none d-md-table-cell">E-mail</th>
                        <th class="text-center">Nível</th>
                        <th class="text-center" style="width: 130px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $u)
                    <tr class="linha-item">
                        <td class="ps-3">
                            @if($u->foto && Illuminate\Support\Facades\Storage::disk('public')->exists($u->foto))
                                <img src="{{ Illuminate\Support\Facades\Storage::url($u->foto) }}" class="rounded-circle border" style="width:32px; height:32px; object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-emerald text-white d-flex align-items-center justify-content-center fw-bold" style="width:32px; height:32px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($u->nome ?? $u->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="fw-bold text-dark text-truncate" style="max-width: 130px;" title="{{ $u->nome ?? $u->name }}">
                            {{ Str::words($u->nome ?? $u->name, 2, '...') }}
                        </td>
                        <td class="text-muted d-none d-md-table-cell small">{{ $u->email }}</td>
                        <td class="text-center">
                            @if(($u->nivel_acesso ?? $u->tipo) === 'admin')
                                <span class="badge bg-success"><i class="fa-solid fa-shield-halved me-1"></i> Admin</span>
                            @else
                                <span class="badge bg-secondary"><i class="fa-solid fa-user me-1"></i> Comum</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(Auth::id() !== $u->id)
                                <div class="btn-group" role="group">
                                    <!-- Botão Editar Usuário -->
                                    <button class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario{{ $u->id }}" title="Editar Usuário">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </button>
                                    
                                    <!-- Botão Resetar Senha -->
                                    <button class="btn btn-sm btn-outline-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalResetSenha{{ $u->id }}" title="Resetar Senha (123456)">
                                        <i class="fa-solid fa-key"></i>
                                    </button>

                                    <!-- Botão Excluir Usuário -->
                                    <button class="btn btn-sm btn-outline-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalExcluirUser{{ $u->id }}" title="Excluir">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            @else
                                <span class="badge bg-light text-muted border">Você</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAIS DO SISTEMA -->
@foreach($usuarios as $u)
    <!-- 1. Modal Editar Usuário (Somente Admin) -->
    <div class="modal fade" id="modalEditarUsuario{{ $u->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('usuarios.update', $u->id) }}" method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-pen me-2"></i> Editar Usuário</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="mb-3 text-center">
                        @if($u->foto && Illuminate\Support\Facades\Storage::disk('public')->exists($u->foto))
                            <img src="{{ Illuminate\Support\Facades\Storage::url($u->foto) }}" class="rounded-circle border mb-2" style="width:64px; height:64px; object-fit:cover;">
                        @endif
                        <div class="text-start">
                            <label class="form-label fw-bold">Alterar Foto (Opcional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome Completo <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="{{ $u->nome ?? $u->name }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">E-mail <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required value="{{ $u->email }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Cargo / Função <span class="text-danger">*</span></label>
                        <select name="cargo" class="form-select" required>
                            <option value="Técnico de TI / Administrador" {{ ($u->cargo === 'Técnico de TI / Administrador' || ($u->nivel_acesso ?? $u->tipo) === 'admin') ? 'selected' : '' }}>Técnico de TI / Administrador</option>
                            <option value="Usuário Comum" {{ ($u->cargo !== 'Técnico de TI / Administrador' && ($u->nivel_acesso ?? $u->tipo) !== 'admin') ? 'selected' : '' }}>Usuário Comum</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nova Senha (Opcional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Deixe em branco para manter a atual">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fa-solid fa-floppy-disk me-1"></i> Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Modal Resetar Senha -->
    <div class="modal fade" id="modalResetSenha{{ $u->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-key me-2"></i> Resetar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="fs-6 mb-2">Deseja redefinir a senha de <strong>{{ $u->nome ?? $u->name }}</strong>?</p>
                    <div class="alert alert-warning mb-0">
                        A nova senha será: <strong>123456</strong>
                    </div>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('usuarios.reset-senha', $u->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning fw-bold text-dark">Redefinir Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Modal Excluir -->
    <div class="modal fade" id="modalExcluirUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i> Excluir</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="fs-5 mb-1">Deseja remover:</p>
                    <p class="fw-bold text-danger fs-5">{{ $u->nome ?? $u->name }}</p>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger fw-bold">Sim, Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Novo Usuário -->
<div class="modal fade" id="modalNovoUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0">
            @csrf
            <div class="modal-header bg-emerald text-white">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-plus me-2"></i> Novo Usuário</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Foto (Opcional)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nome Completo</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: João Silva">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">E-mail</label>
                    <input type="email" name="email" class="form-control" required placeholder="joao@ti.com">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Cargo / Função</label>
                    <select name="cargo" class="form-select" required>
                        <option value="Técnico de TI / Administrador">Técnico de TI / Administrador</option>
                        <option value="Usuário Comum" selected>Usuário Comum</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Senha de Acesso</label>
                    <input type="password" name="password" class="form-control" required placeholder="Mínimo 3 caracteres">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-emerald fw-bold"><i class="fa-solid fa-floppy-disk me-1"></i> Cadastrar</button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT DE BUSCA EM TEMPO REAL -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputBusca = document.getElementById('inputBuscaUsuario');
    const linhas = document.querySelectorAll('#tabelaUsuarios tbody .linha-item');

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