<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marca;
use App\Models\Modelo;
/*El comando para instalar el seeder es:
php artisan db:seed --class=MarcaModeloSeeder*/
class MarcaModeloSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('data/car-models.json')), true);

        foreach ($data as $item) {
            $marca = Marca::create([
                'nombre' => $item['brand']
            ]);

            foreach ($item['models'] as $modeloNombre) {
                Modelo::create([
                    'marca_id' => $marca->id,
                    'nombre' => $modeloNombre
                ]);
            }
        }
    }
}
