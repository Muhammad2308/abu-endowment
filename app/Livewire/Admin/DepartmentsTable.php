<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentsTable extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $departments = Department::with(['faculty', 'visions'])
            ->where('current_name', 'like', '%'.$this->search.'%')
            ->orWhereHas('faculty', function ($query) {
                $query->where('current_name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.departments-table', [
            'departments' => $departments
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
