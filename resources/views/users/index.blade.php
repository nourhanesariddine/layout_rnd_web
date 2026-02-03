@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="bi bi-people me-2"></i>User Management
    </h1>
    <div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add New User
        </a>
    </div>
</div>

@if($users->count() > 0)
    <x-card>
        <x-table :headers="['Name', 'Email', 'Created At', 'Actions']">
            @foreach($users as $user)
                <tr>
                    <td>
                        <strong>{{ $user->name }}</strong>
                        @if($user->id === Auth::id())
                            <span class="badge bg-info ms-2">You</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('users.show', $user) }}" class="action-btn action-btn-view" title="View Details" data-bs-toggle="tooltip">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="action-btn action-btn-edit" title="Edit User" data-bs-toggle="tooltip">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== Auth::id())
                                <button 
                                    type="button" 
                                    class="action-btn action-btn-delete" 
                                    title="Delete User" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </x-card>
@else
    <x-card>
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">No users found</h5>
            <p class="text-muted">Get started by adding your first user.</p>
            <div class="mt-3">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Add New User
                </a>
            </div>
        </div>
    </x-card>
@endif

<x-modal id="deleteModal" title='<i class="bi bi-exclamation-triangle text-danger me-2"></i>Confirm Delete'>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteUserForm" method="POST" class="d-inline" data-base-action="{{ route('users.destroy', ':id') }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash me-1"></i>Delete User
            </button>
        </form>
    </x-slot>
    <div class="text-center">
        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
        <p class="mt-3 mb-2">
            Are you sure you want to delete <strong id="userNameToDelete"></strong>?
        </p>
        <p class="text-muted small mb-0">This action cannot be undone.</p>
    </div>
</x-modal>
@endsection

