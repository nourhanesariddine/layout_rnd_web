@extends('layouts.app')

@section('title', 'Search')

@section('content')
<div class="mb-4">
    <h1 class="h3">
        <i class="bi bi-search me-2"></i>Search & Filter Contacts
    </h1>
</div>

<x-card>
    <form id="searchForm" class="mb-4" data-search-route="{{ route('search.search') }}" data-per-page="{{ $perPage }}">
        @csrf
        <div class="row g-3">
        
            <div class="col-md-4">
                <label for="name" class="form-label">
                    <i class="bi bi-person me-1"></i>Name (Partial Match)
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="name" 
                    name="name" 
                    placeholder="First or last name..."
                    autocomplete="off"
                >
            </div>

        
            <div class="col-md-4">
                <label for="phone" class="form-label">
                    <i class="bi bi-telephone me-1"></i>Phone Number
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="phone" 
                    name="phone" 
                    placeholder="Phone number..."
                    autocomplete="off"
                >
            </div>

          
            <div class="col-md-4">
                <label for="department_id" class="form-label">
                    <i class="bi bi-building me-1"></i>Department
                </label>
                <select class="form-select" id="department_id" name="department_id">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Search
                </button>
                <button type="button" id="clearBtn" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i>Clear
                </button>
            </div>
        </div>
    </form>

    <div id="loadingIndicator" class="text-center py-5" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Searching...</p>
    </div>

    <div id="resultsContainer">
        <div class="text-center py-5">
            <i class="bi bi-search display-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">Start Searching</h5>
            <p class="text-muted">Use the filters above to search for contacts.</p>
        </div>
    </div>
</x-card>
@endsection

