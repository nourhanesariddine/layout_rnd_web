@extends('layouts.app')

@section('title', 'View User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <x-card title='<i class="bi bi-person me-2"></i>User Details'>
            <x-slot name="headerActions">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                @if($user->id !== Auth::id())
                    <button 
                        type="button" 
                        class="btn btn-sm btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal"
                        data-user-id="{{ $user->id }}"
                        data-user-name="{{ $user->name }}"
                    >
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                @endif
            </x-slot>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="mb-1">{{ $user->name }}</h3>
                    @if($user->id === Auth::id())
                        <span class="badge bg-info">Current User</span>
                    @endif
                </div>
            </div>
            
            <hr>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong><i class="bi bi-envelope me-2"></i>Email:</strong>
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
                <div class="col-md-6">
                    <strong><i class="bi bi-calendar me-2"></i>Member Since:</strong>
                    <p class="mb-0">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-12">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Created: {{ $user->created_at ? $user->created_at->format('M d, Y h:i A') : 'N/A' }} | 
                        Updated: {{ $user->updated_at ? $user->updated_at->format('M d, Y h:i A') : 'N/A' }}
                    </small>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>Edit User
                </a>
            </div>
        </x-card>
    </div>
</div>

@if($user->id !== Auth::id())
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
@endif
@endsection
