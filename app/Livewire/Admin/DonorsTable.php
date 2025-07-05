<?php

namespace App\Livewire\Admin;

use App\Models\Donor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class DonorsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function render()
    {
        $donors = Donor::with(['faculty', 'department'])
            ->where(function ($query) {
                $query->where('surname', 'like', '%'.$this->search.'%')
                    ->orWhere('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('reg_number', 'like', '%'.$this->search.'%')
                    ->orWhereHas('faculty', function ($q) {
                        $q->where('current_name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('department', function ($q) {
                        $q->where('current_name', 'like', '%'.$this->search.'%');
                    });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.donors-table', [
            'donors' => $donors
        ]);
    }

    #[On('donor-added')]
    public function refreshTable()
    {
        // This method is intentionally empty.
        // The #[On] attribute will trigger a re-render.
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
