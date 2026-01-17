<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsManager extends Component
{
    public $totalDonations = 0;
    public $totalDonors = 0;
    public $newDonorsThisWeek = 0;
    public $alumniPercentage = 0;
    public $averageDonation = 0;
    
    // Comparisons (vs last month)
    public $donationGrowth = 0;
    public $avgDonationGrowth = 0;

    public $chartData = [];

    public function mount()
    {
        $this->calculateSummaryMetrics();
        $this->prepareChartData();
    }

    public function calculateSummaryMetrics()
    {
        // Totals
        $this->totalDonations = Donation::where('status', 'success')->sum('amount') + Donation::where('status', 'paid')->sum('amount');
        $this->totalDonors = Donor::count();
        $this->averageDonation = Donation::whereIn('status', ['success', 'paid'])->avg('amount') ?? 0;

        // Growth Logic (vs Last Month)
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        $currentMonthDonations = Donation::whereIn('status', ['success', 'paid'])
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])
            ->sum('amount');
            
        $lastMonthDonations = Donation::whereIn('status', ['success', 'paid'])
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');

        $this->donationGrowth = $lastMonthDonations > 0 
            ? (($currentMonthDonations - $lastMonthDonations) / $lastMonthDonations) * 100 
            : 0;

        // New Donors
        $this->newDonorsThisWeek = Donor::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        // Alumni Percentage
        $alumniCount = Donor::where('donor_type', 'alumni')->count();
        $this->alumniPercentage = $this->totalDonors > 0 
            ? round(($alumniCount / $this->totalDonors) * 100, 1) 
            : 0;
    }

    public function prepareChartData()
    {
        // 1. Top Donors (by Name)
        $topDonors = Donation::whereIn('status', ['success', 'paid'])
            ->selectRaw('donor_id, sum(amount) as total')
            ->groupBy('donor_id')
            ->orderByDesc('total')
            ->take(8)
            ->with('donor')
            ->get()
            ->map(function ($row) {
                return [
                    'label' => $row->donor->full_name ?? 'Unknown',
                    'value' => $row->total
                ];
            });

        // 2. Faculties (by Amount Donated)
        $faculties = Donation::whereIn('donations.status', ['success', 'paid'])
            ->whereHas('donor.faculty')
            ->join('donors', 'donations.donor_id', '=', 'donors.id')
            ->join('faculties', 'donors.faculty_id', '=', 'faculties.id')
            ->selectRaw('faculties.current_name as name, sum(donations.amount) as total')
            ->groupBy('faculties.id', 'faculties.current_name')
            ->orderByDesc('total')
            ->take(10)
            ->get()
            ->map(function ($row) {
                return [
                    'label' => $row->name,
                    'value' => $row->total
                ];
            });

        // 3. Departments
        $departments = Donation::whereIn('donations.status', ['success', 'paid'])
            ->whereHas('donor.department') // Ensure donor has department
            ->join('donors', 'donations.donor_id', '=', 'donors.id')
            ->join('departments', 'donors.department_id', '=', 'departments.id')
            ->selectRaw('departments.current_name as name, sum(donations.amount) as total')
            ->groupBy('departments.id', 'departments.current_name') // Group by ID is safer for SQL modes
            ->orderByDesc('total')
            ->take(10)
            ->get()
            ->map(function ($row) {
                return [
                    'label' => $row->name,
                    'value' => $row->total
                ];
            });

        // 4. Projects
        $projects = Donation::whereIn('donations.status', ['success', 'paid'])
            ->whereHas('project') // Ensure donation has a project
            ->join('projects', 'donations.project_id', '=', 'projects.id')
            ->selectRaw('projects.project_title as name, sum(donations.amount) as total')
            ->groupBy('projects.id', 'projects.project_title')
            ->orderByDesc('total')
            ->take(8)
            ->get()
            ->map(function ($row) {
                return [
                    'label' => \Illuminate\Support\Str::limit($row->name, 20), // Truncate long titles
                    'value' => $row->total
                ];
            });

        // 5. States (by Amount Donated)
        $states = Donation::whereIn('donations.status', ['success', 'paid'])
            ->join('donors', 'donations.donor_id', '=', 'donors.id')
            ->whereNotNull('donors.state')
            ->selectRaw('donors.state, sum(donations.amount) as total')
            ->groupBy('donors.state')
            ->orderByDesc('total')
            ->take(8)
            ->get()
            ->map(function ($row) {
                return [
                    'label' => $row->state,
                    'value' => $row->total
                ];
            });

        // 5. LGAs
        $lgas = Donor::selectRaw('lga, count(*) as count')
            ->whereNotNull('lga')
            ->groupBy('lga')
            ->orderByDesc('count')
            ->take(10)
            ->get()
            ->map(function ($row) {
                return [
                    'label' => $row->lga,
                    'value' => $row->count
                ];
            });

        // 6. Gender
        $gender = Donor::selectRaw('gender, count(*) as count')
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get()
            ->map(function ($row) {
                return [
                    'label' => ucfirst($row->gender),
                    'value' => $row->count
                ];
            });

        $this->chartData = [
            'donors' => $this->formatChartData($topDonors),
            'faculties' => $this->formatChartData($faculties), // Replaced donorTypes
            'departments' => $this->formatChartData($departments),
            'projects' => $this->formatChartData($projects),
            'states' => $this->formatChartData($states, true),
            'lgas' => $this->formatChartData($lgas),
            'gender' => $this->formatChartData($gender, true),
        ];
    }

    private function formatChartData($collection, $isPie = false)
    {
        return [
            'labels' => $collection->pluck('label'),
            'datasets' => [
                [
                    'data' => $collection->pluck('value'),
                ]
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.statistics-manager')
            ->layout('layouts.admin');
    }
}
