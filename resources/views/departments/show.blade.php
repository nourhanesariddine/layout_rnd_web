@extends('layouts.app')

@section('title', 'View Department')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <x-card title='<i class="bi bi-building me-2"></i>Department Details'>
            <x-slot name="headerActions">
                <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <button 
                    type="button" 
                    class="btn btn-sm btn-danger" 
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteModal"
                    data-department-id="{{ $department->id }}"
                    data-department-name="{{ $department->name }}"
                >
                    <i class="bi bi-trash me-1"></i>Delete
                </button>
            </x-slot>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="mb-1">{{ $department->name }}</h3>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-12">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Created: {{ $department->created_at->format('M d, Y h:i A') }} | 
                        Updated: {{ $department->updated_at->format('M d, Y h:i A') }}
                    </small>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
                <a href="{{ route('departments.edit', $department) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>Edit Department
                </a>
            </div>
        </x-card>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modal id="deleteModal" title='<i class="bi bi-exclamation-triangle text-danger me-2"></i>Confirm Delete'>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteDepartmentForm" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash me-1"></i>Delete Department
            </button>
        </form>
    </x-slot>
    <div class="text-center">
        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
        <p class="mt-3 mb-2">
            Are you sure you want to delete <strong id="departmentNameToDelete"></strong>?
        </p>
        <p class="text-muted small mb-0">This action cannot be undone.</p>
    </div>
</x-modal>
@endsection

@push('scripts')
<script>
    // Delete confirmation modal handler
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            
            // Extract info from data attributes
            const departmentId = button.getAttribute('data-department-id');
            const departmentName = button.getAttribute('data-department-name');
            
            // Update modal content
            const departmentNameSpan = deleteModal.querySelector('#departmentNameToDelete');
            if (departmentNameSpan) {
                departmentNameSpan.textContent = departmentName;
            }
            
            // Update form action
            const form = deleteModal.querySelector('#deleteDepartmentForm');
            if (form) {
                form.action = '{{ route("departments.destroy", ":id") }}'.replace(':id', departmentId);
            }
        });
    }
</script>
@endpush
