<?php

namespace App\Console\Commands;

use App\Jobs\ProcessBeerJob;
use App\Models\Beer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportBeers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-beers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa cervejas de um arquivo beers.json e dispara jobs para processá-las.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonPath = public_path('beers.json');

        $jsonData = json_decode(file_get_contents($jsonPath), true);

        if(!is_array($jsonData))
        {
            die("Erro ao ler o .json\n");
        }

        foreach ($jsonData as $index => $beerData) {
            $beer = Beer::create([
                'name'            => $beerData['name'] ?? null,
                'tagline'         => $beerData['tagline'] ?? null,
                'description'     => $beerData['description'] ?? null,
                'first_brewed_at' => Carbon::canBeCreatedFromFormat($beerData['first_brewed'], 'm/Y') ?
                    Carbon::createFromFormat('m/Y', $beerData['first_brewed'])
                    : null,
                'abv'             => $beerData['abv'] ?? 1,
                'ibu'             => (int) $beerData['ibu'] ?? 1,
                'ebc'             => $beerData['ebc'] ?? 1,
                'ph'              => $beerData['ph'] ?? 1,
                'volume'          => (int) number_format($beerData['volume']['value'], 0),
                'ingredients'     => json_encode($beerData['ingredients'] ?? []),
                'brewer_tips'     => $beerData['brewers_tips'] ?? null,
            ]);

            dispatch(new ProcessBeerJob($beer))
                ->delay(now()->addSeconds($index * 2));
        }

        $this->info("Importação finalizada com sucesso!");
    }
}
