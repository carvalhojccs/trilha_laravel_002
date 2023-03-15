<?php

namespace App\Http\Livewire\State;

use App\Models\State;
use Livewire\Component;
use Livewire\WithPagination;

class StateIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $editMode = false;

    public $countryId;

    public $name;

    public $stateId;

    // regras de validação
    protected $rules = [
        'countryId' => 'required',
        'name' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->stateId = $id;
        $this->loadState();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-state-modal', 'actionModal' => 'show']);
    }

    public function showStateModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-state-modal', 'actionModal' => 'show']);
    }

    public function loadState()
    {
        $state = State::find($this->stateId);

        $this->countryId = $state->country_id;
        $this->name = $state->name;
    }

    public function updateState()
    {
        $validated = $this->validate([
            'countryId' => 'required',
            'name' => 'required',
        ]);

        $state = State::find($this->stateId);

        $state->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-state-modal', 'actionModal' => 'hide']);
        session()->flash('state-message', 'State successfully updated!');
    }

    public function deleteState($id)
    {
        $state = State::find($id);
        $state->delete();

        session()->flash('state-message', 'State successfully deleted!');
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-state-modal', 'actionModal' => 'hide']);
    }

    public function storeState()
    {
        $this->validate();

        State::create([
            'country_id' => $this->countryId,
            'name' => $this->name,
        ]);

        $this->reset();

        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-state-modal', 'actionModal' => 'hide']);

        session()->flash('state-message', 'State successfully created!');
    }

    public function render()
    {
        $states = State::paginate(5);

        if (strlen($this->search) > 2) {
            $states = State::where('name', 'ilike', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.state.state-index', [
            'states' => $states,
        ])->layout('layouts.main');
    }
}
