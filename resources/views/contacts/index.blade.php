@extends('layouts.app')

@section('title', 'All Contacts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="bi bi-people me-2"></i>All Contacts
    </h1>
    <div>
        <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-upload me-1"></i>Import CSV
        </button>
        <a href="{{ route('contacts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add New Contact
        </a>
    </div>
</div>

@if($contacts->count() > 0)
    <x-card>
        <x-table :headers="['First Name', 'Last Name',  'Phone', 'Birthdate', 'City', 'Actions']">
            @foreach($contacts as $contact)
                <tr>
                    <td>
                      {{ $contact->first_name }}
                    </td>
                    <td>{{ $contact->last_name }}</td>
                    <td>{{ $contact->phone ?? '-' }}</td>
                    <td>{{ $contact->birthdate ? $contact->birthdate->format('d/m/Y') : '-' }}</td>
                    <td>{{ $contact->city ?? '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('contacts.show', $contact) }}" class="action-btn action-btn-view" title="View Details" data-bs-toggle="tooltip">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('contacts.edit', $contact) }}" class="action-btn action-btn-edit" title="Edit Contact" data-bs-toggle="tooltip">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button 
                                type="button" 
                                class="action-btn action-btn-delete" 
                                title="Delete Contact" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal"
                                data-contact-id="{{ $contact->id }}"
                                data-contact-name="{{ $contact->first_name }} {{ $contact->last_name }}"
                            >
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        
        <div class="mt-3">
            {{ $contacts->links() }}
        </div>
    </x-card>
@else
    <x-card>
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">No contacts found</h5>
            <p class="text-muted">Get started by adding your first contact or importing a CSV file.</p>
            <div class="mt-3">
                <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload me-1"></i>Import CSV
                </button>
                <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Add New Contact
                </a>
            </div>
        </div>
    </x-card>
@endif

<!-- Import CSV Modal -->
<x-modal 
    id="importModal"
    title='<i class="bi bi-upload me-2"></i>Import Contacts from CSV'
>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="importForm" class="btn btn-primary">
            <i class="bi bi-upload me-1"></i>Import
        </button>
    </x-slot>
    <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
        @csrf
        <x-form.filefield name="csv_file" label="Select CSV File" accept=".csv,.txt" :required="true" />
        <div class="form-text mb-3">
            CSV format: first_name, last_name, phone, birthdate, city
        </div>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>CSV Format:</strong> The first row should be headers (optional). Required columns: <strong>first_name, last_name</strong>. Optional columns: phone, birthdate (YYYY-MM-DD), city.
        </div>
    </form>
</x-modal>

<!-- Delete Confirmation Modal -->
<x-modal id="deleteModal" title='<i class="bi bi-exclamation-triangle text-danger me-2"></i>Confirm Delete'>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteContactForm" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash me-1"></i>Delete Contact
            </button>
        </form>
    </x-slot>
    <div class="text-center">
        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
        <p class="mt-3 mb-2">
            Are you sure you want to delete <strong id="contactNameToDelete"></strong>?
        </p>
        <p class="text-muted small mb-0">This action cannot be undone.</p>
    </div>
</x-modal>
@endsection

@push('scripts')
<script>
    // Auto-submit form when file is selected (optional enhancement)
    document.getElementById('csv_file')?.addEventListener('change', function(e) {
        if (this.files.length > 0) {
            // File selected, ready to import
        }
    });
    
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    
    // Delete confirmation modal handler
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            
            // Extract info from data attributes
            const contactId = button.getAttribute('data-contact-id');
            const contactName = button.getAttribute('data-contact-name');
            
            // Update modal content
            const contactNameSpan = deleteModal.querySelector('#contactNameToDelete');
            if (contactNameSpan) {
                contactNameSpan.textContent = contactName;
            }
            
            // Update form action
            const form = deleteModal.querySelector('#deleteContactForm');
            if (form) {
                form.action = '{{ route("contacts.destroy", ":id") }}'.replace(':id', contactId);
            }
        });
    }
</script>
@endpush
