<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;

class ViewProjectDetails extends Component
{
    public $showModal = false;
    public $project;

    public function mount(Project $project)  {

        $this->project = $project;

        // dd($this->project);
        
    }

    #[On('open-view-project-modal')]
    public function openModal($payload = [])
    {
        dd('sojdjfdjfjdfjdjfjdfjdjfjd');
        $projectId = is_array($payload) ? ($payload['projectId'] ?? null) : null;
        if ($projectId) {
            $this->project = Project::findOrFail($projectId);
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->project = null;
    }

    public function render()
    {
        return view('livewire.admin.view-project-details');
    }
} 