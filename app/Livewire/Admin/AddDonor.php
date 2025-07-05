<?php

namespace App\Livewire\Admin;

use App\Models\Donor;
use App\Models\Faculty;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\On;

class AddDonor extends Component
{
    public $showModal = false;

    // Form fields
    public $name;
    public $email;
    public $phone;
    public $graduation_year;
    public $faculty_id;
    public $department_id;

    public $faculties;
    public $departments = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:donors,email',
        'phone' => 'nullable|string|max:20',
        'graduation_year' => 'required|digits:4|integer|min:1900',
        'faculty_id' => 'required|exists:faculties,id',
        'department_id' => 'required|exists:departments,id',
    ];

    public function mount()
    {
        $this->faculties = Faculty::orderBy('current_name')->get();
    }

    #[On('open-add-donor-modal')]
    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function updatedFacultyId($value)
    {
        if ($value) {
            $this->departments = Department::where('faculty_id', $value)->orderBy('name')->get();
            $this->department_id = null; // Reset department selection
        } else {
            $this->departments = [];
        }
    }

    public function saveDonor()
    {
        $this->validate();

        Donor::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'graduation_year' => $this->graduation_year,
            'faculty_id' => $this->faculty_id,
            'department_id' => $this->department_id,
        ]);

        session()->flash('message', 'Donor added successfully.');
        $this->dispatch('donor-added');
        $this->closeModal();
    }
    
    public function resetForm()
    {
        $this->reset(['name', 'email', 'phone', 'graduation_year', 'faculty_id', 'department_id']);
        $this->departments = [];
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.add-donor');
    }
} 