<?php

namespace App\Livewire\Beers;

use App\Livewire\Forms\BeerForm;
use App\Models\Beer;
use Livewire\Component;

class Create extends Component
{
    public BeerForm $form;
    public Beer $beer;

    public function save(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $this->authorize('create', Beer::class);
        $beer = $this->form->store();

        return redirect(route('beers.index'))
            ->success("{$this->form->name} criada com sucesso!");
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.beers.create');
    }
}
