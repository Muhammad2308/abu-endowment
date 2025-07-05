<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Faculty;
use Livewire\WithPagination;
use Livewire\Attributes\On; 

class FacultiesTable extends Component
{
    use WithPagination;

    public $search = '';

    #[On('facultiesUpdated')] 
    #[On('departmentAdded')] 
    public function render()
    {
        $faculties = Faculty::with(['departments', 'visions'])
            ->where('current_name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.faculties-table', [
            'faculties' => $faculties
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
