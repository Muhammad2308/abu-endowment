<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Donation;
use App\Models\Project;
use App\Models\Faculty;
use App\Models\Department;

use Carbon\Carbon;

class AnalyticsDrillDown extends Component
{
    use WithPagination;

    public $filterType;
    public $filterValue;
    public $title;

    public function mount($type, $value)
    {
        $this->filterType = $type;
        $this->filterValue = $value;
        $this->setTitle();
    }

    public function setTitle()
    {
        $value = urldecode($this->filterValue);
        switch ($this->filterType) {
            case 'faculty':
                $this->title = "Donations for Faculty: $value";
                break;
            case 'department':
                $this->title = "Donations for Department: $value";
                break;
            case 'project':
                // Try to find project name if value is ID, otherwise use value
                $project = Project::find($value);
                $name = $project ? $project->project_title : $value;
                $this->title = "Donations for Project: $name";
                break;
            case 'state':
                $this->title = "Donations from State: $value";
                break;
            case 'lga':
                $this->title = "Donations from LGA: $value";
                break;
            case 'gender':
                $this->title = "Donations by Gender: " . ucfirst($value);
                break;
            default:
                $this->title = "Donation Details";
        }
    }

    public function export()
    {
        $query = Donation::with(['donor.faculty', 'donor.department', 'project'])
            ->whereIn('status', ['success', 'paid', 'completed'])
            ->latest();

        $value = urldecode($this->filterValue);

        // Apply same filters as render
        switch ($this->filterType) {
            case 'faculty':
                $query->whereHas('donor.faculty', function ($q) use ($value) {
                    $q->where('current_name', $value);
                });
                break;
            case 'department':
                $query->whereHas('donor.department', function ($q) use ($value) {
                    $q->where('current_name', $value);
                });
                break;
            case 'project':
                $query->whereHas('project', function ($q) use ($value) {
                    $q->where('project_title', $value);
                });
                break;
            case 'state':
                $query->whereHas('donor', function ($q) use ($value) {
                    $q->where('state', $value);
                });
                break;
            case 'lga':
                $query->whereHas('donor', function ($q) use ($value) {
                    $q->where('lga', $value);
                });
                break;
            case 'gender':
                $query->whereHas('donor', function ($q) use ($value) {
                    $q->where('gender', 'LIKE', $value); 
                });
                break;
        }

        $donations = $query->get();
        $totalAmount = $donations->sum('amount');

        $fileName = 'analytics_report_' . Carbon::now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($donations, $totalAmount) {
            $file = fopen('php://output', 'w');
            
            // Header Row
            fputcsv($file, [
                'Date', 
                'Donor Name', 
                'Email', 
                'Phone', 
                'Amount (NGN)', 
                'Type', 
                'Project', 
                'Faculty', 
                'Department', 
                'State', 
                'LGA', 
                'Status'
            ]);

            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->created_at->format('Y-m-d H:i:s'),
                    $donation->donor->full_name ?? 'Guest',
                    $donation->donor->email ?? '',
                    $donation->donor->phone ?? '',
                    $donation->amount,
                    ucfirst($donation->type),
                    $donation->project->project_title ?? 'N/A',
                    $donation->donor->faculty->current_name ?? 'N/A',
                    $donation->donor->department->current_name ?? 'N/A',
                    $donation->donor->state ?? 'N/A',
                    $donation->donor->lga ?? 'N/A',
                    ucfirst($donation->status)
                ]);
            }

            // Empty row
            fputcsv($file, []);

            // Total Row
            fputcsv($file, ['', '', '', 'TOTAL AMOUNT:', $totalAmount, '', '', '', '', '', '', '']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $query = Donation::with(['donor.faculty', 'donor.department', 'project'])
            ->whereIn('status', ['success', 'paid', 'completed'])
            ->latest();

        $value = urldecode($this->filterValue);

        switch ($this->filterType) {
            case 'faculty':
                $query->whereHas('donor.faculty', function ($q) use ($value) {
                    $q->where('current_name', $value);
                });
                break;
            case 'department':
                $query->whereHas('donor.department', function ($q) use ($value) {
                    $q->where('current_name', $value);
                });
                break;
            case 'project':
                // Check if value is ID or Name, usually charts pass labels (Name)
                // But for projects, we might have passed ID or Name. Let's assume Name for consistency with charts
                // unless we change charts to pass hidden IDs.
                // For now, let's look for project title
                $query->whereHas('project', function ($q) use ($value) {
                    $q->where('project_title', $value);
                });
                break;
            case 'state':
                $query->whereHas('donor', function ($q) use ($value) {
                    $q->where('state', $value);
                });
                break;
            case 'lga':
                $query->whereHas('donor', function ($q) use ($value) {
                    $q->where('lga', $value);
                });
                break;
            case 'gender':
                $query->whereHas('donor', function ($q) use ($value) {
                    // Normalize gender check just in case
                    $q->where('gender', 'LIKE', $value); 
                });
                break;
        }

        return view('livewire.admin.analytics-drill-down', [
            'donations' => $query->paginate(15)
        ])->layout('layouts.admin');
    }
}
