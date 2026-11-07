<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Saídas - {{ $mes }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none !important; } }
        body { font-family: Arial, sans-serif; background-color: #ffffff; padding: 15mm; }
    </style>
</head>

<body>
    <div class="text-center mb-3 no-print">
        <button onclick="window.print()" class="btn btn-success fw-bold"><i class="fa-solid fa-print"></i> Confirmar Impressão / Salvar PDF</button>
    </div>

    <div class="text-center mb-4 border-bottom pb-3">
        <h3 class="fw-bold m-0">DEPARTAMENTO DE TECNOLOGIA DA INFORMAÇÃO</h3>
        <h5 class="text-muted">RELATÓRIO MENSAL DE SAÍDA DE MATERIAIS</h5>
        <p class="m-0"><strong>Período:</strong> {{ date('m/Y', strtotime($mes . '-01')) }} | <strong>Total Despachado:</strong> {{ $totalItensDespachados }} itens</p>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th width="15%">Data/Hora</th>
                <th width="30%">Equipamento</th>
                <th width="10%" class="text-center">Qtd</th>
                <th width="25%">Destino/Setor</th>
                <th width="20%">Motivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($saidas as $s)
            <tr>
                <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                <td><strong>{{ $s->produto->nome ?? 'N/A' }}</strong></td>
                <td class="text-center"><strong>{{ $s->quantidade }}</strong></td>
                <td>{{ $s->destinatario }}</td>
                <td><small>{{ $s->observacao ?? '-' }}</small></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-5 text-center pt-4">
        <div class="col-6">
            ________________________________________<br>
            <strong>Responsável do Almoxarifado TI</strong>
        </div>
       
    </div>
</body>
</html>