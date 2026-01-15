<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Donation;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;

class ReportsManager extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $selectedFaculty = '';
    public $selectedDepartment = '';
    // public $selectedProgramme = ''; // Not implemented in DB yet
    public $selectedProject = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $minAmount = '';
    public $maxAmount = '';
    public $donorPhone = '';
    public $donorName = '';
    public $selectedStatus = ''; // New status filter
    
    public $perPage = 10;

    public function updated($propertyName)
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'search', 'selectedFaculty', 'selectedDepartment', 
            'selectedProject', 'dateFrom', 'dateTo', 
            'minAmount', 'maxAmount', 'donorPhone', 'donorName',
            'selectedStatus'
        ]);
        $this->resetPage();
    }

    public function getFilteredQuery()
    {
        $query = Donation::query()
            ->with(['donor', 'project', 'donor.faculty', 'donor.department']);

        // Filter by Faculty (Donor's)
        if ($this->selectedFaculty) {
            $query->whereHas('donor', function (Builder $q) {
                $q->where('faculty_id', $this->selectedFaculty);
            });
        }

        // Filter by Department (Donor's)
        if ($this->selectedDepartment) {
            $query->whereHas('donor', function (Builder $q) {
                $q->where('department_id', $this->selectedDepartment);
            });
        }

        // Filter by Project
        if ($this->selectedProject) {
            $query->where('project_id', $this->selectedProject);
        }

        // Filter by Date Range
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Filter by Amount
        if ($this->minAmount) {
            $query->where('amount', '>=', $this->minAmount);
        }
        if ($this->maxAmount) {
            $query->where('amount', '<=', $this->maxAmount);
        }

        // Filter by Donor Phone
        if ($this->donorPhone) {
            $query->whereHas('donor', function (Builder $q) {
                $q->where('phone', 'like', '%' . $this->donorPhone . '%');
            });
        }

        // Filter by Donor Name (or global search)
        if ($this->donorName) {
            $query->whereHas('donor', function (Builder $q) {
                // SQLite specific concatenation for full name search
                $q->whereRaw(
                    "(surname || ' ' || name || ' ' || COALESCE(other_name, '') LIKE ? 
                    OR name || ' ' || surname || ' ' || COALESCE(other_name, '') LIKE ?)", 
                    ['%' . $this->donorName . '%', '%' . $this->donorName . '%']
                );
            });
        }
        
        // Filter by Status
        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }
        
        // Global Search fallback
        if ($this->search) {
             $query->where(function($q) {
                $q->whereHas('donor', function ($d) {
                    $d->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('surname', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('payment_reference', 'like', '%' . $this->search . '%');
            });
        }

        return $query;
    }

    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DonationsExport($this->getFilteredQuery()), 
            'donations_report_' . date('Y-m-d_H-i') . '.xlsx'
        );
    }

    public function updatedSelectedFaculty()
    {
        $this->selectedDepartment = '';
    }

    public function render()
    {
        $donations = $this->getFilteredQuery()->latest()->paginate($this->perPage);

        $departmentsRequest = Department::query()->orderBy('current_name');
        
        if ($this->selectedFaculty) {
            $departmentsRequest->where('faculty_id', $this->selectedFaculty);
        }

        return view('livewire.admin.reports-manager', [
            'donations' => $donations,
            'faculties' => Faculty::orderBy('current_name')->get(),
            'departments' => $departmentsRequest->get(),
            'projects' => Project::orderBy('project_title')->get(),
        ]);
    }
}
