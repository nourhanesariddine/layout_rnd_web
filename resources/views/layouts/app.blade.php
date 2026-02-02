<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @yield('title', 'Contact Management') - {{ config('app.name', 'Laravel') }}</title>
    
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('contacts.index') }}">
                <i class="bi bi-person-lines-fill me-2"></i>Contact Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('search.index') }}">
                            <i class="bi bi-search me-1"></i>Search
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacts.index') }}">
                            <i class="bi bi-people me-1"></i>Contacts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('departments.index') }}">
                            <i class="bi bi-building me-1"></i>Departments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="bi bi-people me-1"></i>Users
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">
                                    <i class="bi bi-person me-2"></i>My Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modern Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer" style="z-index: 9999">
        @php
            $successMessage = session()->pull('success');
            $warningMessage = session()->pull('warning');
            $errorMessage = session()->pull('error');
            $importErrors = session()->pull('import_errors');
        @endphp

        @if($successMessage)
            <div id="successToast" class="toast modern-toast toast-success" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000">
                <div class="toast-content">
                    <div class="toast-icon-wrapper bg-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="toast-body-content">
                        <div class="toast-title">Success</div>
                        <div class="toast-message">{{ $successMessage }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if($warningMessage)
            <div id="warningToast" class="toast modern-toast toast-warning" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="6000">
                <div class="toast-content">
                    <div class="toast-icon-wrapper bg-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="toast-body-content">
                        <div class="toast-title">Warning</div>
                        <div class="toast-message">{{ $warningMessage }}</div>
                        @if($importErrors && is_array($importErrors) && count($importErrors) > 0)
                            <details class="mt-2">
                                <summary class="small cursor-pointer">Show {{ count($importErrors) }} error(s)</summary>
                                <ul class="mb-0 small mt-2">
                                    @foreach(array_slice($importErrors, 0, 10) as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    @if(count($importErrors) > 10)
                                        <li class="text-muted">... and {{ count($importErrors) - 10 }} more error(s)</li>
                                    @endif
                                </ul>
                            </details>
                        @endif
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if($errorMessage)
            <div id="errorToast" class="toast modern-toast toast-error" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="6000">
                <div class="toast-content">
                    <div class="toast-icon-wrapper bg-danger">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="toast-body-content">
                        <div class="toast-title">Error</div>
                        <div class="toast-message">{{ $errorMessage }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div id="errorToast" class="toast modern-toast toast-error" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="6000">
                <div class="toast-content">
                    <div class="toast-icon-wrapper bg-danger">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="toast-body-content">
                        <div class="toast-title">Validation Error</div>
                        <div class="toast-message">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <main class="container my-4">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
