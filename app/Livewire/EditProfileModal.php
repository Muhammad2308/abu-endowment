<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\UserInfo;

class EditProfileModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $address;
    public $phone;
    public $states = [];
    public $lgas = [];
    public $selectedState = '';
    public $selectedCity = '';
    public $profile_photo;
    public $current_photo;
    public $successMessage;
    public $asCard = false;

    protected $rules = [
        'address' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:255',
        'selectedState' => 'nullable|string|max:255',
        'selectedCity' => 'nullable|string|max:255',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    ];

    public function mount()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            // Not logged in, set defaults or skip loading user info
            $this->address = '';
            $this->phone = '';
            $this->selectedState = '';
            $this->selectedCity = '';
            $this->current_photo = null;
            return;
        }
        // Load LGA.json
        $lgaJson = file_get_contents(public_path('LGA.json'));
        $lgaData = json_decode($lgaJson, true);
        $this->states = array_keys($lgaData);
        $this->lgas = $lgaData;
        $userInfo = $user->userInfo;
        $this->address = $userInfo->address ?? '';
        $this->phone = $userInfo->phone ?? '';
        $this->selectedState = $userInfo->state ?? '';
        $this->selectedCity = $userInfo->city ?? '';
        $this->current_photo = $userInfo->profile_photo ?? null;
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetErrorBag();
        $this->successMessage = null;
    }

    public function updatedSelectedState($value)
    {
        $this->selectedCity = '';
    }

    public function updateProfile()
    {
        $this->validate();
        $user = Auth::user();
        $userInfo = $user->userInfo;
        $data = [
            'address' => $this->address,
            'phone' => $this->phone,
            'state' => $this->selectedState,
            'city' => $this->selectedCity,
        ];
        if ($this->profile_photo) {
            $path = $this->profile_photo->store('profile_photos', 'public');
            $data['profile_photo'] = $path;
            $this->current_photo = $path;
        }
        if ($userInfo) {
            $userInfo->update($data);
        } else {
            $data['user_id'] = $user->id;
            UserInfo::create($data);
        }
        $this->successMessage = 'Profile updated successfully!';
        $this->reset('profile_photo');
        $this->dispatch('profile-updated');
    }

    public function render()
    {
        return view('livewire.edit-profile-modal', [
            'asCard' => $this->asCard,
        ]);
    }
}
