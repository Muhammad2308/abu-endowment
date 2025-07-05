<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
// use Livewire\Attributes\On;

class ProjectsManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    protected $listeners = ['project-added' => '$refresh'];
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // #[On('project-added')]
    // public function refreshTable()
    // {
    //     // This will trigger a re-render
    // }

    public function render()
    {
        $projects = Project::query()
            ->when($this->search, function ($query) {
                $query->where('project_title', 'like', '%' . $this->search . '%')
                      ->orWhere('project_description', 'like', '%' . $this->search . '%');
            })
            ->withCount('photos')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.projects-manager', [
            'projects' => $projects
        ]);
    }
}
