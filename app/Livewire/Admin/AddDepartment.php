<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\Faculty;
use Livewire\Component;

class AddDepartment extends Component
{
    public $showModal = false;
    public $faculty;
    public $departmentName = '';
    public $successMessage = '';
    public $errorMessage = '';

    protected $listeners = ['showAddDepartmentModal'];

    public function showAddDepartmentModal(Faculty $faculty)
    {
        $this->reset();
        $this->faculty = $faculty;
        $this->showModal = true;
    }

    public function rules()
    {
        return [
            'departmentName' => 'required|string|max:255|unique:departments,name,NULL,id,faculty_id,' . $this->faculty->id,
        ];
    }

    public function messages()
    {
        return [
            'departmentName.unique' => 'This department already exists in this faculty.',
        ];
    }

    public function saveDepartment()
    {
        $this->validate();

        try {
            Department::create([
                'current_name' => $this->departmentName,
                'faculty_id' => $this->faculty->id,
            ]);

            $this->successMessage = 'Department added successfully.';
            $this->dispatch('departmentAdded'); // To refresh data if needed
            $this->reset('departmentName');
            
            // Keep the modal open to add another or show success
            // $this->showModal = false; 

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while adding the department.';
            // Log::error('Add Department Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.add-department');
    }
}
