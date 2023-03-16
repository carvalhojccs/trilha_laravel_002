<?php

namespace App\Http\Livewire\Employee;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $editMode = false;

    public $employeeId;

    public $lastName;

    public $firstName;

    public $middleName;

    public $address;

    public $zipCode;

    public $birthdate;

    public $dateHired;

    public $departmentId;

    public $countryId;

    public $stateId;

    public $cityId;

    public $selectedDepartmentId = null;

    protected $rules = [
        'lastName' => 'required',
        'firstName' => 'required',
        'middleName' => 'required',
        'address' => 'required',
        'zipCode' => 'required',
        'birthdate' => 'required',
        'dateHired' => 'required',
        'departmentId' => 'required',
        'countryId' => 'required',
        'stateId' => 'required',
        'cityId' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        
        $this->employeeId = $id;

        $employee = Employee::find($id);

        $this->lastName = $employee->last_name;
        $this->firstName = $employee->first_name;
        $this->middleName = $employee->middle_name;
        $this->address = $employee->address;
        $this->zipCode = $employee->zip_code;
        $this->birthdate = $employee->birthdate;
        $this->dateHired = $employee->date_hired;
        $this->departmentId = $employee->department_id;
        $this->countryId = $employee->country_id;
        $this->stateId = $employee->state_id;
        $this->cityId = $employee->city_id;

        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-employee-modal', 'actionModal' => 'show']);
    }

    public function showEmployeeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-employee-modal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-employee-modal', 'actionModal' => 'hide']);
    }

    public function storeEmployee()
    {
        $this->validate();

        Employee::create([
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'address' => $this->address,
            'zip_code' => $this->zipCode,
            'birthdate' => $this->birthdate,
            'date_hired' => $this->dateHired,
            'department_id' => $this->departmentId,
            'country_id' => $this->countryId,
            'state_id' => $this->stateId,
            'city_id' => $this->cityId,
        ]);

        $this->reset();

        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-employee-modal', 'actionModal' => 'hide']);

        session()->flash('employee-message', 'Employee successfully created!');
    }

    public function updateEmployee()
    {
        $this->validate();       

        $employee = Employee::find($this->employeeId);      

        $employee->update([
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'address' => $this->address,
            'zip_code' => $this->zipCode,
            'birthdate' => $this->birthdate,
            'date_hired' => $this->dateHired,
            'department_id' => $this->departmentId,
            'country_id' => $this->countryId,
            'state_id' => $this->stateId,
            'city_id' => $this->cityId,
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#new-employee-modal', 'actionModal' => 'hide']);

        session()->flash('employee-message', 'Employee successfully updated!');
    }

    public function deleteEmployee($id)
    {
        $employee = Employee::find($id);
        $employee->delete();

        session()->flash('employee-message', 'Employee successfully deleted!');
    }

    public function render()
    {
        $employees = Employee::paginate(5);

        if (strlen($this->search) > 2) {
            if ($this->selectedDepartmentId) {
                $employees = Employee::where('first_name', 'ilike', "%{$this->search}%")
                            ->where('department_id', $this->selectedDepartmentId)
                            ->paginate(5);
            } else {
                $employees = Employee::where('department_id', $this->selectedDepartmentId)->paginate(5);
            }
        } else if ($this->selectedDepartmentId) {
            $employees = Employee::where('department_id', $this->selectedDepartmentId)->paginate(5);
        }

        return view('livewire.employee.employee-index', compact('employees'))->layout('layouts.main');
    }
}
