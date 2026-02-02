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
@endsection
