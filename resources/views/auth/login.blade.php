<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestão de TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #0f5132;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-login {
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
        }
        .btn-emerald {
            background-color: #198754;
            color: white;
        }
        .btn-emerald:hover { background-color: #0f5132; color: white; }
    </style>
</head>
<body>
    <div class="card card-login border-0 shadow-lg p-4 bg-white">
        <div class="text-center mb-4">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Mirante do Paranapanema" class="img-fluid mb-2" style="max-height: 70px; width: auto; object-fit: contain;">
            <h4 class="fw-bold text-dark">Gestão de TI</h4>
            <p class="text-muted small">Almoxarifado & Controle de Estoque</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-secondary small fw-bold">E-MAIL</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="admin@ti.com" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-secondary small fw-bold">SENHA</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-emerald w-100 py-2 fw-bold shadow-sm">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Acessar Sistema
            </button>
        </form>
    </div>
</body>
</html>