@extends('layouts.app')

@section('title', 'Search')

@section('content')
<div class="mb-4">
    <h1 class="h3">
        <i class="bi bi-search me-2"></i>Search & Filter Contacts
    </h1>
</div>

<x-card>
    <form id="searchForm" class="mb-4">
        @csrf
        <div class="row g-3">
            <!-- Name Filter -->
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

            <!-- Phone Filter -->
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

            <!-- Department Filter -->
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

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center py-5" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Searching...</p>
    </div>

    <!-- Results Container -->
    <div id="resultsContainer">
        <div class="text-center py-5">
            <i class="bi bi-search display-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">Start Searching</h5>
            <p class="text-muted">Use the filters above to search for contacts.</p>
        </div>
    </div>
</x-card>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const resultsContainer = document.getElementById('resultsContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const clearBtn = document.getElementById('clearBtn');
    
    let searchTimeout;

    // Handle form submission
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    // Real-time search on input (with debounce)
    const nameInput = document.getElementById('name');
    const phoneInput = document.getElementById('phone');
    const departmentSelect = document.getElementById('department_id');

    [nameInput, phoneInput, departmentSelect].forEach(element => {
        element.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                // Only search if at least one field has value
                if (nameInput.value || phoneInput.value || departmentSelect.value) {
                    performSearch();
                } else {
                    clearResults();
                }
            }, 500); // 500ms debounce
        });
    });

    // Handle department select change
    departmentSelect.addEventListener('change', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            if (nameInput.value || phoneInput.value || departmentSelect.value) {
                performSearch();
            } else {
                clearResults();
            }
        }, 300);
    });

    // Clear button
    clearBtn.addEventListener('click', function() {
        searchForm.reset();
        clearResults();
    });

    function performSearch(page = 1) {
        const formData = new FormData(searchForm);
        formData.append('page', page);
        
        // Show loading
        loadingIndicator.style.display = 'block';
        resultsContainer.style.display = 'none';

        fetch('{{ route("search.search") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.style.display = 'none';
            resultsContainer.style.display = 'block';
            
            if (data.success) {
                resultsContainer.innerHTML = data.html;
                
                // Re-attach pagination event listeners
                attachPaginationListeners();
                
                // Initialize tooltips
                initializeTooltips();
            } else {
                resultsContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>Error performing search. Please try again.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.style.display = 'none';
            resultsContainer.style.display = 'block';
            resultsContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>An error occurred. Please try again.
                </div>
            `;
        });
    }

    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('.pagination-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');
                performSearch(page);
            });
        });
    }

    function initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    function clearResults() {
        resultsContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">Start Searching</h5>
                <p class="text-muted">Use the filters above to search for contacts.</p>
            </div>
        `;
    }
});
</script>
@endpush
