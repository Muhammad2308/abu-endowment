<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\ProjectPhoto;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class AddProjectPhotos extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $projectId;
    public $project;
    public $photos = [];

    protected $rules = [
        'photos.*' => 'required|image|max:5120', // 5MB max per image
    ];

    protected $messages = [
        'photos.*.required' => 'Please select at least one photo.',
        'photos.*.image' => 'Each file must be an image.',
        'photos.*.max' => 'Each image size must not exceed 5MB.',
    ];

    #[On('open-add-photos-modal')]
    public function openModal($projectId)
    {
        $this->resetForm();
        $this->projectId = $projectId;
        $this->project = Project::with('photos')->findOrFail($projectId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->projectId = null;
        $this->project = null;
        $this->photos = [];
        $this->resetValidation();
    }

    public function savePhotos()
    {
        $this->validate();

        try {
            foreach ($this->photos as $photo) {
                $photoPath = $photo->store('projects/photos', 'public');
                ProjectPhoto::create([
                    'project_id' => $this->projectId,
                    'body_image' => $photoPath,
                ]);
            }

            // Clear the file input for new uploads
            $this->reset('photos');

            // Reload the project with its photos so the gallery updates
            $this->project = Project::with('photos')->find($this->projectId);

            session()->flash('message', 'Photos added successfully!');
            // Optionally, you can dispatch an event if another component needs to know
            // $this->dispatch('photos-added');

        } catch (\Exception $e) {
            session()->flash('error', 'Error adding photos: ' . $e->getMessage());
        }
    }

    public function deletePhoto($photoId)
    {
        try {
            $photo = ProjectPhoto::findOrFail($photoId);
            
            // Delete the file from storage
            if ($photo->body_image) {
                Storage::disk('public')->delete($photo->body_image);
            }
            
            $photo->delete();
            
            session()->flash('message', 'Photo deleted successfully!');
            $this->dispatch('photo-deleted');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting photo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        if ($this->project) {
            $this->project->load('photos');
        }
        
        return view('livewire.admin.add-project-photos');
    }
} 