<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Gestão de TI') </title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (Ícones) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --emerald-main: #059669;
            --emerald-hover: #047857;
            --emerald-light: #d1fae5;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .bg-emerald { background-color: var(--emerald-main) !important; }
        .text-emerald { color: var(--emerald-main) !important; }
        .btn-emerald {
            background-color: var(--emerald-main);
            color: #fff;
            border: none;
        }
        .btn-emerald:hover {
            background-color: var(--emerald-hover);
            color: #fff;
        }
        .btn-outline-emerald {
            border-color: var(--emerald-main);
            color: var(--emerald-main);
        }
        .btn-outline-emerald:hover {
            background-color: var(--emerald-main);
            color: #fff;
        }
        .bg-emerald-light { background-color: var(--emerald-light) !important; }
        .border-emerald { border-color: var(--emerald-main) !important; }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
        }
        .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* PERFIL DO USUÁRIO NO DESKTOP (ESTILO MODERNO) */
        .user-profile-link {
            background-color: rgba(255, 255, 255, 0.12);
            padding: 0.35rem 0.85rem;
            border-radius: 50rem;
            transition: all 0.25s ease-in-out;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .user-profile-link:hover {
            background-color: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        /* REGRAS PARA MOBILE */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background-color: var(--emerald-main);
                padding: 1rem;
                border-radius: 0.5rem;
                margin-top: 0.5rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
            .navbar-nav .nav-link {
                margin-bottom: 0.25rem;
            }
            .user-menu-mobile {
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                padding-bottom: 0.75rem;
                margin-bottom: 0.75rem;
            }
        }

        /* pra rolagem M dO MOBILE nao quebrar */
        html, body {
            overflow-x: hidden;
        }
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
		
		/* Customização da Paginação para o Verde Emerald */
		.pagination .page-link {
			color: var(--emerald-main);
			border-color: #dee2e6;
		}
		.pagination .page-link:hover {
			color: var(--emerald-hover);
			background-color: var(--emerald-light);
			border-color: #dee2e6;
		}
		.pagination .page-item.active .page-link {
			background-color: var(--emerald-main) !important;
			border-color: var(--emerald-main) !important;
			color: #ffffff !important;
		}
		.pagination .page-item.disabled .page-link {
			color: #6c757d;
		}
				/* Esconde o texto "Showing X to Y of Z results" da paginação */
		.pagination-wrapper small,
		div > p.small.text-muted {
			display: none !important;
}
    </style>
</head>
<body>

    <!-- NAVBAR RESPONSIVA -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-emerald shadow-sm sticky-top">
        <div class="container-fluid px-3 px-md-4">
            
            <!-- Logo -->
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-boxes-stacked me-2 fs-4"></i>
                <span>Gestão de TI</span>
            </a>

            <!-- Botão para Celular (Mobile) -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
                <i class="fa-solid fa-bars fs-3 text-white"></i>
            </button>

            <!-- Links do Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                
                <!-- MENU DO USUÁRIO NO MOBILE  -->
                <div class="d-flex d-lg-none align-items-center justify-content-between user-menu-mobile">
                    <a href="{{ route('perfil') }}" class="text-white text-decoration-none d-flex align-items-center gap-2">
                        @if(Auth::user()->foto && Illuminate\Support\Facades\Storage::disk('public')->exists(Auth::user()->foto))
                            <img src="{{ Illuminate\Support\Facades\Storage::url(Auth::user()->foto) }}" class="rounded-circle border border-white" style="width:34px; height:34px; object-fit:cover;">
                        @else
                            <i class="fa-solid fa-circle-user fs-3"></i>
                        @endif
                        <div>
                            <span class="fw-bold d-block lh-1">{{ Auth::user()->name ?? 'Usuário' }}</span>
                            <small class="text-white-50" style="font-size: 0.75rem;">Ver meu perfil</small>
                        </div>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light text-emerald fw-semibold d-flex align-items-center gap-1 shadow-sm" title="Sair do Sistema">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Sair</span>
                        </button>
                    </form>
                </div>

                <!-- LINKS DE NAVEGAÇÃO -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-chart-line me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('produtos.*') ? 'active' : '' }}" href="{{ route('produtos.index') }}">
                            <i class="fa-solid fa-box-archive me-1"></i> Almoxarifado
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('saidas.*') ? 'active' : '' }}" href="{{ route('saidas.index') }}">
                            <i class="fa-solid fa-truck-ramp-box me-1"></i> Saídas / Baixas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}" href="{{ route('relatorios.index') }}">
                            <i class="fa-solid fa-file-invoice me-1"></i> Relatórios
                        </a>
                    </li>
                    @if(Auth::check() && Auth::user()->tipo === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                            <i class="fa-solid fa-users-gear me-1"></i> Usuários
                        </a>
                    </li>
                    @endif
                </ul>

                <!-- MENU DO USUÁRIO NO DESKTOP -->
                <div class="d-none d-lg-flex align-items-center gap-3">
                    <a href="{{ route('perfil') }}" class="text-white text-decoration-none d-flex align-items-center gap-2 user-profile-link">
                        @if(Auth::user()->foto && Illuminate\Support\Facades\Storage::disk('public')->exists(Auth::user()->foto))
                            <img src="{{ Illuminate\Support\Facades\Storage::url(Auth::user()->foto) }}" class="rounded-circle border border-white" style="width:28px; height:28px; object-fit:cover;">
                        @else
                            <i class="fa-solid fa-circle-user fs-5 text-white-50"></i>
                        @endif
                        <span class="fw-semibold small pe-1">{{ Auth::user()->name ?? 'Usuário' }}</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light text-emerald fw-semibold d-flex align-items-center gap-1 shadow-sm rounded-pill px-3" title="Sair do Sistema">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Sair</span>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="container-fluid px-3 px-md-4 py-4">
        @yield('conteudo') <!-- preenche com o conteudo -->
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>