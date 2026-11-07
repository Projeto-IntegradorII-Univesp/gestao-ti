@extends('layouts.app')

@section('titulo', 'Perfil')

@section('conteudo')

{{-- Alertas de Sucesso ou Erro --}}
@if(session('sucesso'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('sucesso') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="mb-4">
    <h3 class="text-emerald fw-bold m-0"><i class="fa-solid fa-id-badge me-2"></i> Meu Perfil</h3>
    <p class="text-muted">Atualize seus dados de acesso e foto de exibição no sistema.</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4">
            <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="d-flex align-items-center gap-3 mb-4">
                    @if($user->foto && Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto))
                        <img src="{{ Illuminate\Support\Facades\Storage::url($user->foto) }}" class="rounded-circle border shadow-sm" width="80" height="80" style="object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-emerald text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:80px; height:80px; font-size:2rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <label class="form-label fw-bold small text-muted">ALTERAR FOTO DE PERFIL</label>
                        <input type="file" name="foto" class="form-control form-control-sm" accept="image/*">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nome Completo</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">E-mail de Acesso</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <!-- CAMPO CARGO / FUNÇÃO COM VALIDAÇÃO SE É ADMIN OU COMUM -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Cargo / Função</label>
                    
                    @if($user->tipo === 'admin')
                        <!-- Se for ADMIN: Pode alterar livremente -->
                        <select name="cargo" class="form-select" required>
                            <option value="Técnico de TI / Administrador" {{ $user->cargo == 'Técnico de TI / Administrador' || $user->tipo == 'admin' ? 'selected' : '' }}>Técnico de TI / Administrador</option>
                            <option value="Usuário Comum" {{ $user->cargo == 'Usuário Comum' || $user->tipo == 'comum' ? 'selected' : '' }}>Usuário Comum</option>
                        </select>
                    @else
                        <!-- Se for USUÁRIO COMUM: Campo bloqueado (Disabled) -->
                        <input type="text" class="form-control bg-light" value="{{ $user->cargo ?: 'Usuário Comum' }}" disabled readonly>
                        <!-- Envia o valor oculto para não quebrar a validação se o Controller exigir -->
                        <input type="hidden" name="cargo" value="{{ $user->cargo ?: 'Usuário Comum' }}">
                        <small class="text-muted"><i class="fa-solid fa-lock me-1"></i> A alteração de cargo só pode ser realizada por um Administrador do sistema.</small>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Nova Senha (Opcional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Deixe em branco para manter a senha atual">
                </div>

                <button type="submit" class="btn btn-emerald fw-bold">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Salvar Alterações
                </button>
            </form>
        </div>
    </div>
</div>
@endsection