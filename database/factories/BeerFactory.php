<?php

namespace Database\Factories;

use App\Models\Beer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories.Factory<\App\Models\Beer>
 */
class BeerFactory extends Factory
{
    protected $model = Beer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Estilos com faixas realistas de ABV, IBU e EBC
        $styles = [
            'Pilsen' => [
                'abv' => [4.0, 5.0],
                'ibu' => [8, 18],
                'ebc' => [4, 8],
            ],
            'American Lager' => [
                'abv' => [4.2, 5.3],
                'ibu' => [8, 20],
                'ebc' => [4, 10],
            ],
            'IPA' => [
                'abv' => [5.5, 7.5],
                'ibu' => [40, 80],
                'ebc' => [10, 25],
            ],
            'NE IPA' => [
                'abv' => [6.0, 8.0],
                'ibu' => [30, 60],
                'ebc' => [8, 20],
            ],
            'Stout' => [
                'abv' => [5.0, 8.0],
                'ibu' => [30, 50],
                'ebc' => [60, 120],
            ],
            'Porter' => [
                'abv' => [4.5, 6.5],
                'ibu' => [20, 40],
                'ebc' => [40, 80],
            ],
            'Witbier' => [
                'abv' => [4.5, 5.5],
                'ibu' => [10, 20],
                'ebc' => [4, 10],
            ],
            'Weissbier' => [
                'abv' => [4.5, 5.5],
                'ibu' => [10, 20],
                'ebc' => [6, 14],
            ],
            'Saison' => [
                'abv' => [5.0, 7.5],
                'ibu' => [20, 35],
                'ebc' => [8, 20],
            ],
            'Amber Ale' => [
                'abv' => [4.5, 6.2],
                'ibu' => [25, 40],
                'ebc' => [20, 35],
            ],
        ];

        $styleName = $this->faker->randomElement(array_keys($styles));
        $style = $styles[$styleName];

        $abv = $this->faker->randomFloat(1, $style['abv'][0], $style['abv'][1]);
        $ibu = $this->faker->numberBetween($style['ibu'][0], $style['ibu'][1]);
        $ebc = $this->faker->numberBetween($style['ebc'][0], $style['ebc'][1]);

        // Volumes mais comuns no mercado
        $volume = $this->faker->randomElement([330, 350, 355, 473, 500, 600]);

        // “Marcas” e nomes de linha mais realistas
        $breweryNames = [
            'Serra Alta',
            'Rio Claro',
            'Pedra Branca',
            'Brassaria Central',
            'Linha do Trem',
            'Porto Velho',
            'Casa do Malte',
        ];

        $beerLineNames = [
            'Aurora',
            'Bruma',
            'Raízes',
            'Origem',
            'Brassagem 01',
            'Oficina',
            'Fronteira',
        ];

        $brewery = $this->faker->randomElement($breweryNames);
        $line = $this->faker->randomElement($beerLineNames);

        // Ex: “Aurora IPA - Serra Alta”
        $name = "{$line} {$styleName} - {$brewery}";

        // Ingredientes um pouco mais variados
        $malts = ['Pilsen', 'Pale Ale', 'Munich', 'Caramel', 'Chocolate', 'Trigo'];
        $hops = ['Cascade', 'Citra', 'Mosaic', 'Amarillo', 'Hallertau', 'Saaz', 'Simcoe'];
        $yeasts = ['American Ale', 'German Lager', 'Belgian Ale', 'Wit', 'Weiss'];

        $ingredients = sprintf(
            'Maltes: %s. Lúpulos: %s. Levedura: %s. Água tratada.',
            implode(', ', $this->faker->randomElements($malts, $this->faker->numberBetween(2, 3))),
            implode(', ', $this->faker->randomElements($hops, $this->faker->numberBetween(1, 3))),
            $this->faker->randomElement($yeasts)
        );

        // pH típico de cerveja (4.0 a 4.6)
        $ph = $this->faker->randomFloat(1, 4.0, 4.6);

        return [
            'name'           => $name,
            'tagline'        => $this->faker->randomElement([
                'Cerveja artesanal fresca e equilibrada.',
                'Notas cítricas e aroma intenso de lúpulo.',
                'Leve, refrescante e fácil de beber.',
                'Maltada, encorpada e com final seco.',
                'Perfil frutado e aroma tropical marcante.',
                'Perfeita para acompanhar uma boa refeição.',
            ]),
            'description'    => $this->faker->paragraphs(2, true),
            'first_brewed_at'=> $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'abv'            => $abv,
            'ibu'            => $ibu,
            'ebc'            => $ebc,
            'ph'             => $ph,
            'volume'         => $volume, // ml
            'ingredients'    => $ingredients,
            'brewer_tips'    => $this->faker->randomElement([
                'Servir entre 4°C e 6°C para destacar a refrescância.',
                'Harmoniza bem com carnes grelhadas e queijos fortes.',
                'Experimente com pratos apimentados para realçar o amargor do lúpulo.',
                'Ideal para acompanhar uma tábua de frios.',
                'Agite levemente o final da garrafa para servir o fermento e intensificar o sabor.',
            ]),
        ];
    }
}
