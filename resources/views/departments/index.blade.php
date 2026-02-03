@extends('layouts.app')

@section('title', 'All Departments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="bi bi-building me-2"></i>All Departments
    </h1>
    <div>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add New Department
        </a>
    </div>
</div>

@if($departments->count() > 0)
    <x-card>
        <x-table :headers="['Name', 'Created At', 'Actions']">
            @foreach($departments as $department)
                <tr>
                    <td>
                        <strong>{{ $department->name }}</strong>
                    </td>
                    <td>{{ $department->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('departments.show', $department) }}" class="action-btn action-btn-view" title="View Details" data-bs-toggle="tooltip">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('departments.edit', $department) }}" class="action-btn action-btn-edit" title="Edit Department" data-bs-toggle="tooltip">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button 
                                type="button" 
                                class="action-btn action-btn-delete" 
                                title="Delete Department" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal"
                                data-department-id="{{ $department->id }}"
                                data-department-name="{{ $department->name }}"
                            >
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        
        <div class="mt-3">
            {{ $departments->links() }}
        </div>
    </x-card>
@else
    <x-card>
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">No departments found</h5>
            <p class="text-muted">Get started by adding your first department.</p>
            <div class="mt-3">
                <a href="{{ route('departments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Add New Department
                </a>
            </div>
        </div>
    </x-card>
@endif

<x-modal id="deleteModal" title='<i class="bi bi-exclamation-triangle text-danger me-2"></i>Confirm Delete'>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteDepartmentForm" method="POST" class="d-inline" data-base-action="{{ route('departments.destroy', ':id') }}">
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

