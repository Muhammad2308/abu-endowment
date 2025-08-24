<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\ProjectPhoto;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AddProjectPhotos extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public Project $project;
    public $photos = [];

    protected $rules = [
        'photos.*' => 'required|image|max:5120', // 5MB max per image
    ];

    protected $messages = [
        'photos.*.required' => 'Please select at least one photo.',
        'photos.*.image' => 'Each file must be an image.',
        'photos.*.max' => 'Each image size must not exceed 5MB.',
    ];

    public function open()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
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
                    'project_id' => $this->project->id,
                    'body_image' => $photoPath,
                ]);
            }

            $this->reset('photos');
            $this->project->load('photos'); // Reload photos relationship

            session()->flash('message', 'Photos added successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding photos: ' . $e->getMessage());
        }
    }

    public function deletePhoto($photoId)
    {
        try {
            $photo = ProjectPhoto::findOrFail($photoId);
            
            if ($photo->body_image) {
                Storage::disk('public')->delete($photo->body_image);
            }
            
            $photo->delete();
            
            $this->project->load('photos'); // Reload photos relationship
            session()->flash('message', 'Photo deleted successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting photo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.add-project-photos');
    }
}