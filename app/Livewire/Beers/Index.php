<?php

namespace App\Livewire\Beers;

use Livewire\Component;

class Index extends Component
{

    public string $sortBy = '';
    public string $sortDirection = '';

    public function sort($sortBy)
    {
        $this->sortBy = $sortBy;
        $this->sortDirection = !empty($this->sortDirection) && $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        return view('livewire.beers.index');
    }
}
