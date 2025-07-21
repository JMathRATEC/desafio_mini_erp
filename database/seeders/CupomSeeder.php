<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cupom;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CupomSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            if ($faker->boolean) {
                $valorFixo  = $faker->randomFloat(2, 5, 100);
                $percentual = null;
            } else {
                $valorFixo  = null;
                $percentual = $faker->randomFloat(2, 5, 30);
            }

            Cupom::create([
                'codigo'               => strtoupper($faker->unique()->bothify('??####??')),
                'desconto_valor'       => $valorFixo,
                'desconto_percentual'  => $percentual,
                'valor_minimo'         => $faker->randomFloat(2, 0, 300),
                'data_validade'        => Carbon::now()->addDays($faker->numberBetween(1, 90)),
            ]);
        }
    }
}
