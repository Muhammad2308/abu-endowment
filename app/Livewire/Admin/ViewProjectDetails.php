<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;

class ViewProjectDetails extends Component
{
    public $showModal = false;
    public $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.admin.view-project-details');
    }
}