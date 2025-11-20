<?php

namespace App\Livewire\Beers;

use App\Livewire\Forms\BeerForm;
use App\Models\Beer;
use Livewire\Component;

class Update extends Component
{
    public BeerForm $form;
    public Beer $beer;

    public function mount(Beer $beer): void
    {
        $this->authorize('update', $beer);
        $this->beer = $beer;
        $this->form->setBeer($beer);
    }

    public function save(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', $this->beer);
        $this->form->update();
        return redirect(route('beers.index'))
            ->success("{$this->form->name} atualizada com sucesso!");
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.beers.update');
    }
}
