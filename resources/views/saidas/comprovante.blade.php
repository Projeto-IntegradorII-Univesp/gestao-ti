<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Termo de Cautela - #{{ $saida->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none !important; } }
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .page-a4 { width: 210mm; min-height: 297mm; padding: 20mm; margin: 10mm auto; background: white; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="text-center mb-3 no-print">
        <button onclick="window.print()" class="btn btn-success"><i class="fa-solid fa-print"></i> Imprimir Recibo A4</button>
    </div>

    <div class="page-a4">
        <div class="text-center mb-4 border-bottom pb-3">
            <h3 class="fw-bold m-0">PREFEITURA MUNICIPAL / SETOR DE TI</h3>
            <h5 class="text-muted">TERMO DE RESPONSABILIDADE E CAUTELA DE EQUIPAMENTO</h5>
            <small>Comprovante Nº: #{{ str_pad($saida->id, 6, '0', STR_PAD_LEFT) }}</small>
        </div>

        <div class="mb-4">
            <p>Declaramos que o setor/servidor abaixo especificado recebeu do Departamento de Tecnologia da Informação o seguinte material/equipamento em perfeitas condições de uso:</p>
        </div>

        <table class="table table-bordered mb-4">
            <tr>
                <th width="30%">Data/Hora da Retirada:</th>
                <td>{{ $saida->created_at->format('d/m/Y às H:i') }}</td>
            </tr>
            <tr>
                <th>Destinatário / Setor:</th>
                <td><strong>{{ $saida->destinatario }}</strong></td>
            </tr>
            <tr>
                <th>Item Entregue:</th>
                <td>{{ $saida->produto->nome ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Quantidade:</th>
                <td><strong>{{ $saida->quantidade }} unidade(s)</strong></td>
            </tr>
            <tr>
                <th>Observações:</th>
                <td>{{ $saida->observacao ?? 'Nenhuma observação registrada.' }}</td>
            </tr>
        </table>

        <div style="margin-top: 80mm;">
            <div class="row text-center">
                <div class="col-6">
                    _______________________________________<br>
                    <strong>Responsável pela Entrega (TI)</strong>
                </div>
                <div class="col-6">
                    _______________________________________<br>
                    <strong>Assinatura do Recebedor</strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>