@php
    $minResults = config('search.min_results_for_pagination', 5);
    $showPagination = $contacts->total() > $minResults && $contacts->hasPages();
@endphp

@if($contacts->count() > 0)
    <div class="mb-3">
        <h5 class="mb-3">
            <i class="bi bi-people me-2"></i>Search Results 
            <span class="badge bg-primary">{{ $contacts->total() }}</span>
        </h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>City</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                    <tr>
                        <td>
                            <strong>{{ $contact->first_name }} {{ $contact->last_name }}</strong>
                        </td>
                        <td>
                            @if($contact->phone)
                                <i class="bi bi-telephone me-1"></i>{{ $contact->phone }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($contact->departments && $contact->departments->count() > 0)
                                @foreach($contact->departments as $department)
                                    <span class="badge bg-info me-1">
                                        <i class="bi bi-building me-1"></i>{{ $department->name }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">No Departments</span>
                            @endif
                        </td>
                        <td>
                            @if($contact->city)
                                <i class="bi bi-geo-alt me-1"></i>{{ $contact->city }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('contacts.show', $contact) }}" class="action-btn action-btn-view" title="View Details" data-bs-toggle="tooltip">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('contacts.edit', $contact) }}" class="action-btn action-btn-edit" title="Edit Contact" data-bs-toggle="tooltip">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($showPagination)
        <div class="mt-3">
            <nav aria-label="Search results pagination">
                <ul class="pagination justify-content-center" id="paginationLinks">
                    @if($contacts->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link pagination-link" href="#" data-page="{{ $contacts->currentPage() - 1 }}">Previous</a>
                        </li>
                    @endif

                    @for($i = 1; $i <= $contacts->lastPage(); $i++)
                        <li class="page-item {{ $contacts->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link pagination-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor

                    @if($contacts->hasMorePages())
                        <li class="page-item">
                            <a class="page-link pagination-link" href="#" data-page="{{ $contacts->currentPage() + 1 }}">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>No contacts found matching your search criteria.
    </div>
@endif
