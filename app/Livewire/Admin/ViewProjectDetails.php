<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;

class ViewProjectDetails extends Component
{
    public $showModal = false;
    public $project;

    #[On('open-view-project-modal')]
    public function openModal($projectId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->showModal = true;
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