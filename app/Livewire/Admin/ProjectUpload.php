<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProjectsImport;
use Illuminate\Support\Facades\Log;

class ProjectUpload extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $excel_file;
    public $importedCount = 0;
    public $skippedCount = 0;
    public $failures = [];
    public $errorMessage = '';

    protected $rules = [
        'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
    ];

    protected $messages = [
        'excel_file.required' => 'Please select an Excel file.',
        'excel_file.file' => 'The file must be a valid file.',
        'excel_file.mimes' => 'The file must be an Excel file (xlsx or xls).',
        'excel_file.max' => 'The file size must not exceed 10MB.',
    ];

    #[On('open-project-upload-modal')]
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
        $this->excel_file = null;
        $this->importedCount = 0;
        $this->skippedCount = 0;
        $this->failures = [];
        $this->errorMessage = '';
        $this->resetValidation();
    }

    public function uploadProjects()
    {
        $this->validate();

        try {
            $import = new ProjectsImport();
            
            Excel::import($import, $this->excel_file);
            
            $this->importedCount = $import->getImportedCount();
            $this->skippedCount = $import->getSkippedCount();
            $this->failures = $import->failures()->toArray();

            if ($this->importedCount > 0) {
                session()->flash('message', "Successfully imported {$this->importedCount} projects!");
                $this->dispatch('projects-uploaded');
            } else {
                session()->flash('error', "No projects were imported. {$this->skippedCount} records skipped.");
            }

        } catch (\Exception $e) {
            Log::error('Project upload error: ' . $e->getMessage());
            $this->errorMessage = 'Error uploading projects: ' . $e->getMessage();
            session()->flash('error', $this->errorMessage);
        }
    }

    public function render()
    {
        return view('livewire.admin.project-upload');
    }
} 