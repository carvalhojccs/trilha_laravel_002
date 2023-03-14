<?php

namespace App\Http\Livewire\Country;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class CountryIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $countryCode;

    public $name;

    public $countryId;

    public $editMode = false;

    protected $rules = [
        'countryCode' => 'required',
        'name' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->editMode = true;
        $this->countryId = $id;

        $this->loadCountry();

        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-country-modal', 'actionModal' => 'show']);
    }

    public function loadCountry()
    {
        $country = Country::find($this->countryId);

        $this->countryCode = $country->country_code;
        $this->name = $country->name;
    }

    public function updateCountry()
    {
        $validated = $this->validate([
            'countryCode' => 'required',
            'name' => 'required',
        ]);

        $country = Country::find($this->countryId);
        $country->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-country-modal', 'actionModal' => 'hide']);
        session()->flash('country-message', 'Country successfully updated!');
    }

    public function deleteCountry($id)
    {
        $country = Country::find($id);
        $country->delete();

        session()->flash('country-message', 'Coutry successfully deleted!');
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-country-modal', 'actionModal' => 'hide']);
    }

    public function storeCountry()
    {
        $this->validate();

        Country::create([
            'country_code' => $this->countryCode,
            'name' => $this->name,
        ]);

        $this->reset();

        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-country-modal', 'actionModal' => 'hide']);

        session()->flash('country-message', 'Country successfully created!');
    }

    public function showCountryModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-country-modal', 'actionModal' => 'show']);
    }

    public function render()
    {
        $countries = Country::paginate(5);

        if (strlen($this->search) > 2) {
            $countries = Country::where('name', 'ilike', "%{$this->search}%")->paginate();
        }

        return view('livewire.country.country-index', [
            'countries' => $countries,
        ])->layout('layouts.main');
    }
}
