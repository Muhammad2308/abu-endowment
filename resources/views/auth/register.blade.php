@extends('layouts.charifit')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Create an Account</h4>
                </div>
                <div class="card-body p-5">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5>Please fix the following errors:</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" id="registrationForm">
                        @csrf

                        <!-- Donor Type -->
                        <div class="mb-3">
                            <label for="donor_type" class="form-label">Donor Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('donor_type') is-invalid @enderror" 
                                    id="donor_type" name="donor_type" required>
                                <option value="">-- Select Donor Type --</option>
                                <option value="Individual" {{ old('donor_type') == 'Individual' ? 'selected' : '' }}>Individual</option>
                                <option value="Alumni" {{ old('donor_type') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                                <option value="Supporter" {{ old('donor_type') == 'Supporter' ? 'selected' : '' }}>Supporter</option>
                                <option value="Staff" {{ old('donor_type') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                <option value="Corporate" {{ old('donor_type') == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                                <option value="Organization" {{ old('donor_type') == 'Organization' ? 'selected' : '' }}>Organization</option>
                                <option value="NGO" {{ old('donor_type') == 'NGO' ? 'selected' : '' }}>NGO</option>
                            </select>
                            @error('donor_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Surname -->
                        <div class="mb-3">
                            <label for="surname" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('surname') is-invalid @enderror" 
                                   id="surname" name="surname" value="{{ old('surname') }}" required>
                            @error('surname')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone (Optional) -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Faculty (for Alumni) -->
                        <div id="alumniFields" style="display: none;">
                            <div class="mb-3">
                                <label for="faculty_id" class="form-label">Faculty</label>
                                <select class="form-control @error('faculty_id') is-invalid @enderror" 
                                        id="faculty_id" name="faculty_id" onchange="loadDepartments()">
                                    <option value="">-- Select Faculty --</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                            {{ $faculty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('faculty_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-control @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id">
                                    <option value="">-- Select Department --</option>
                                    @if (old('department_id'))
                                        <option value="{{ old('department_id') }}" selected>
                                            {{ old('department_id') }}
                                        </option>
                                    @endif
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="entry_year" class="form-label">Entry Year</label>
                                    <input type="number" class="form-control @error('entry_year') is-invalid @enderror" 
                                           id="entry_year" name="entry_year" value="{{ old('entry_year') }}" min="1950" max="{{ date('Y') }}">
                                    @error('entry_year')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="graduation_year" class="form-label">Graduation Year</label>
                                    <input type="number" class="form-control @error('graduation_year') is-invalid @enderror" 
                                           id="graduation_year" name="graduation_year" value="{{ old('graduation_year') }}" min="1950" max="{{ date('Y') }}">
                                    @error('graduation_year')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="reg_number" class="form-label">Registration Number</label>
                                <input type="text" class="form-control @error('reg_number') is-invalid @enderror" 
                                       id="reg_number" name="reg_number" value="{{ old('reg_number') }}">
                                @error('reg_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Fields (Optional) -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" name="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state') }}">
                                @error('state')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lga" class="form-label">LGA</label>
                                <input type="text" class="form-control @error('lga') is-invalid @enderror" 
                                       id="lga" name="lga" value="{{ old('lga') }}">
                                @error('lga')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror" 
                                       id="nationality" name="nationality" value="{{ old('nationality', 'Nigerian') }}">
                                @error('nationality')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="{{ route('login.form') }}">Log in here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Show/hide alumni fields based on donor type
document.getElementById('donor_type').addEventListener('change', function() {
    const alumniFields = document.getElementById('alumniFields');
    if (this.value === 'Alumni' || this.value === 'Staff') {
        alumniFields.style.display = 'block';
    } else {
        alumniFields.style.display = 'none';
    }
});

// Trigger on page load for old values
window.addEventListener('load', function() {
    const donorType = document.getElementById('donor_type').value;
    if (donorType === 'Alumni' || donorType === 'Staff') {
        document.getElementById('alumniFields').style.display = 'block';
    }
});

// Load departments via AJAX
function loadDepartments() {
    const facultyId = document.getElementById('faculty_id').value;
    if (!facultyId) {
        document.getElementById('department_id').innerHTML = '<option value="">-- Select Department --</option>';
        return;
    }

    fetch(`{{ route('get.departments', '') }}/${facultyId}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">-- Select Department --</option>';
            data.forEach(dept => {
                options += `<option value="${dept.id}">${dept.name}</option>`;
            });
            document.getElementById('department_id').innerHTML = options;
        })
        .catch(error => console.error('Error loading departments:', error));
}
</script>

<style>
    .text-danger {
        color: #dc3545;
    }
    
    .card {
        border-radius: 10px;
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection
