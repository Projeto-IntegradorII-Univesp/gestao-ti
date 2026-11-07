<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $itens = [
            ['nome' => 'Limpa Contato Spray 300ml', 'categoria' => 'Suprimentos', 'quantidade' => 100, 'estoque_minimo' => 15],
            ['nome' => 'Desengripante Spray 300ml', 'categoria' => 'Suprimentos', 'quantidade' => 100, 'estoque_minimo' => 15],
            ['nome' => 'Fonte de Alimentação ATX 200W', 'categoria' => 'Peças/Hardware', 'quantidade' => 100, 'estoque_minimo' => 10],
            ['nome' => 'Memória RAM 8GB DDR3 1600MHz', 'categoria' => 'Peças/Hardware', 'quantidade' => 100, 'estoque_minimo' => 10],
            ['nome' => 'Memória RAM 8GB DDR4 2666MHz', 'categoria' => 'Peças/Hardware', 'quantidade' => 100, 'estoque_minimo' => 10],
            ['nome' => 'Conector RJ45 Cat5e / Cat6 (Pacote)', 'categoria' => 'Cabos/Redes', 'quantidade' => 100, 'estoque_minimo' => 20],
            ['nome' => 'Monitor LED 21.5" Full HD HDMI', 'categoria' => 'Periféricos', 'quantidade' => 100, 'estoque_minimo' => 5],
            ['nome' => 'Teclado USB ABNT2 Preto', 'categoria' => 'Periféricos', 'quantidade' => 100, 'estoque_minimo' => 15],
            ['nome' => 'Mouse Óptico USB 1200 DPI', 'categoria' => 'Periféricos', 'quantidade' => 100, 'estoque_minimo' => 15],
            ['nome' => 'Placa de Rede Wi-Fi Dual Band 5GHz PCIe', 'categoria' => 'Cabos/Redes', 'quantidade' => 100, 'estoque_minimo' => 10],
        ];

        foreach ($itens as $item) {
            Produto::updateOrCreate(
                ['nome' => $item['nome']],
                [
                    'categoria' => $item['categoria'],
                    'quantidade' => $item['quantidade'],
                    'estoque_minimo' => $item['estoque_minimo'],
                    'status' => true
                ]
            );
        }
    }
}