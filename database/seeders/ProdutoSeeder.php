<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;
use App\Models\Estoque;
use Faker\Factory as Faker;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');

        for ($i = 1; $i <= 10; $i++) {
            $product = Produto::create([
                'nome'  => ucfirst($faker->words(2, true)),
                'preco' => $faker->randomFloat(2, 10, 500),
            ]);

            Estoque::create([
                'produto_id' => $product->id,
                'variacao'   => null,
                'quantidade' => $faker->numberBetween(5, 100),
            ]);
        }
    }
}
