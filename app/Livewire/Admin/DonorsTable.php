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
    public $showDonationsModal = false;
    public $selectedDonor = null;
    public $selectedDonations = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = $this->search;

        $donors = Donor::with(['faculty', 'department'])
            ->where(function ($query) use ($search) {
                $query->where('surname', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('reg_number', 'like', "%{$search}%")
                    ->orWhereHas('faculty', function ($q) use ($search) {
                        $q->where('current_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('current_name', 'like', "%{$search}%");
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

    public function showDonations($donorId)
    {
        $donor = \App\Models\Donor::with('donations')->find($donorId);
        $this->selectedDonor = $donor;
        $this->selectedDonations = $donor ? $donor->donations : [];
        $this->showDonationsModal = true;
    }
}
