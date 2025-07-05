<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class AddProject extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $project_title = '';
    public $project_description = '';
    public $icon_image;

    protected $rules = [
        'project_title' => 'required|string|max:255',
        'project_description' => 'required|string',
        'icon_image' => 'nullable|image|max:2048', // 2MB max
    ];

    protected $messages = [
        'project_title.required' => 'Project title is required.',
        'project_description.required' => 'Project description is required.',
        'icon_image.image' => 'The file must be an image.',
        'icon_image.max' => 'The image size must not exceed 2MB.',
    ];

    #[On('open-add-project-modal')]
    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->project_title = '';
        $this->project_description = '';
        $this->icon_image = null;
        $this->resetValidation();
    }

    public function saveProject()
    {
        Log::info('saveProject method called.');

        $validatedData = $this->validate();
        Log::info('Validation successful.', $validatedData);

        try {
            $iconImagePath = null;
            
            if ($this->icon_image) {
                Log::info('Icon image present. Storing...');
                $iconImagePath = $this->icon_image->store('projects/icons', 'public');
                Log::info('Icon image stored at: ' . $iconImagePath);
            }

            $projectData = [
                'project_title' => $this->project_title,
                'project_description' => $this->project_description,
                'icon_image' => $iconImagePath,
            ];

            Log::info('Creating project with data:', $projectData);
            Project::create($projectData);
            Log::info('Project created successfully.');

            $this->closeModal();
            $this->dispatch('project-added');
            session()->flash('message', 'Project added successfully!');
            
        } catch (\Exception $e) {
            Log::error('Error adding project: ' . $e->getMessage());
            session()->flash('error', 'Error adding project: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.add-project');
    }
} 