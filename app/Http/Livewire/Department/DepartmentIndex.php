<?php

namespace App\Http\Livewire\Department;

use App\Models\Department;
use JetBrains\PhpStorm\Deprecated;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $departmentId;
    public $name;
    public $editMode = false;

    protected $rules = [
        'name' => 'required'
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->editMode = true;
        $this->departmentId = $id;

        $this->loadDepartment();

        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-department-modal', 'actionModal' => 'show']);
    }

    public function loadDepartment()
    {
        $department = Department::find($this->departmentId);

        $this->name = $department->name;
    }

    public function updateDepartment()
    {
        $validated = $this->validate([
            'name' => 'required'
        ]);

        $department = Department::find($this->departmentId);
        $department->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-department-modal', 'actionModal' => 'hide']);
        session()->flash('department-message', 'Department successfully updated!');
    }

    public function deleteDepartment($id)
    {
        $department = Department::find($id);
        $department->delete();

        session()->flash('department-message', 'Department successfully deleted!');
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-department-modal', 'actionModal' => 'hide']);
    }

    public function storeDepartment()
    {
        $this->validate();

        Department::create([
            'name' => $this->name,
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-department-modal', 'actionModal' => 'hide']);

        session()->flash('department-message', 'Department successfully created!');
    }

    public function showDepartmentModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-department-modal', 'actionModal' => 'show']);
    }

    public function render()
    {
        $departments = Department::paginate(5);

        if (strlen($this->search) > 2) {
            $departments = Department::where('name', 'ilike', "%{$this->search}%")->paginate(5);
        }
        return view('livewire.department.department-index', compact('departments'))
            ->layout('layouts.main');
    }
}
