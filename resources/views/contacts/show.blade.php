@extends('layouts.app')

@section('title', 'View Contact')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <x-card title='<i class="bi bi-person me-2"></i>Contact Details'>
            <x-slot name="headerActions">
                <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this contact?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
            </x-slot>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h3 class="mb-1">{{ $contact->first_name }} {{ $contact->last_name }}</h3>
                        @if($contact->job_title)
                            <p class="text-muted mb-0">{{ $contact->job_title }}</p>
                        @endif
                    </div>
                </div>
                
                <hr>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong><i class="bi bi-telephone me-2"></i>Phone:</strong>
                        <p class="mb-0">{{ $contact->phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="bi bi-calendar me-2"></i>Birthdate:</strong>
                        <p class="mb-0">{{ $contact->birthdate ? $contact->birthdate->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="bi bi-geo-alt me-2"></i>City:</strong>
                        <p class="mb-0">{{ $contact->city ?? '-' }}</p>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong><i class="bi bi-building me-2"></i>Departments:</strong>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#manageDepartmentsModal">
                                <i class="bi bi-pencil me-1"></i>Manage Departments
                            </button>
                        </div>
                        @if($contact->departments && $contact->departments->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($contact->departments as $department)
                                    <span class="badge bg-info">
                                        <i class="bi bi-building me-1"></i>{{ $department->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No departments assigned</p>
                        @endif
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            Created: {{ $contact->created_at->format('M d, Y h:i A') }} | 
                            Updated: {{ $contact->updated_at->format('M d, Y h:i A') }}
                        </small>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to List
                    </a>
                 
                </div>
        </x-card>
    </div>
</div>

<x-modal id="manageDepartmentsModal" title='<i class="bi bi-building me-2"></i>Manage Departments'>
    <form action="{{ route('contacts.departments.update', $contact) }}" method="POST" id="manageDepartmentsForm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="department_search" class="form-label">Select Departments</label>
            <div class="position-relative">
                <input 
                    type="text" 
                    class="form-control" 
                    id="department_search" 
                    placeholder="Search departments..."
                    autocomplete="off"
                >
                <div class="dropdown-menu w-100" id="departmentDropdown" style="max-height: 250px; overflow-y: auto; display: none;">
                    @foreach($departments as $department)
                        <label class="dropdown-item d-flex align-items-center" style="cursor: pointer; margin: 0;">
                            <input 
                                type="checkbox" 
                                class="form-check-input me-2" 
                                name="department_ids[]" 
                                value="{{ $department->id }}"
                                id="dept_{{ $department->id }}"
                                {{ $contact->departments->contains($department->id) ? 'checked' : '' }}
                            >
                            <span>{{ $department->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div id="selectedDepartments" class="mt-2 d-flex flex-wrap gap-2">
                @foreach($contact->departments as $department)
                    <span class="badge bg-primary d-flex align-items-center" data-dept-id="{{ $department->id }}">
                        {{ $department->name }}
                        <button type="button" class="btn-close btn-close-white ms-2" style="font-size: 0.7rem;" onclick="removeDepartment({{ $department->id }})"></button>
                    </span>
                @endforeach
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>Save Changes
            </button>
        </div>
    </form>
</x-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('department_search');
    const dropdown = document.getElementById('departmentDropdown');
    const selectedContainer = document.getElementById('selectedDepartments');
    
    if (!searchInput || !dropdown) return;
    
    searchInput.addEventListener('focus', function() {
        dropdown.style.display = 'block';
    });
    
    searchInput.addEventListener('blur', function(e) {
        setTimeout(() => {
            if (!dropdown.contains(e.relatedTarget)) {
                dropdown.style.display = 'none';
            }
        }, 200);
    });
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = dropdown.querySelectorAll('.dropdown-item');
        
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedBadges();
        });
    });
    
    function updateSelectedBadges() {
        selectedContainer.innerHTML = '';
        const checked = dropdown.querySelectorAll('input[type="checkbox"]:checked');
        
        checked.forEach(checkbox => {
            const deptId = checkbox.value;
            const deptName = checkbox.closest('.dropdown-item').querySelector('span').textContent.trim();
            
            const badge = document.createElement('span');
            badge.className = 'badge bg-primary d-flex align-items-center';
            badge.setAttribute('data-dept-id', deptId);
            badge.innerHTML = deptName + 
                '<button type="button" class="btn-close btn-close-white ms-2" style="font-size: 0.7rem;" onclick="removeDepartment(' + deptId + ')"></button>';
            selectedContainer.appendChild(badge);
        });
    }
    
    window.removeDepartment = function(deptId) {
        const checkbox = document.getElementById('dept_' + deptId);
        if (checkbox) {
            checkbox.checked = false;
            updateSelectedBadges();
        }
    };
});
</script>
@endpush
@endsection
