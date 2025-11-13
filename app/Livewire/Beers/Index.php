<?php

namespace App\Livewire\Beers;

use App\Models\Beer;
use App\Services\BeerService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    protected BeerService $beerService;
    public string $sortBy = '';
    public string $sortDirection = '';

    public function boot(BeerService $beerService)
    {
        $this->beerService = $beerService;
    }

    public function sort($sortBy)
    {
        $this->sortBy = $sortBy;
        $this->sortDirection = !empty($this->sortDirection) && $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.beers.index', [
            'beers' => $this->beerService->getBeers($this->sortBy, $this->sortDirection),
        ]);
    }
}
