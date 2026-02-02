<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <x-card title='<i class="bi bi-shield-lock me-2"></i>Admin Login' class="shadow">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <x-form.textfield 
                                name="email" 
                                label='<i class="bi bi-envelope me-1"></i>Email Address'
                                type="email"
                                input_type="email"
                                value="{{ old('email') }}"
                                placeholder="admin@example.com"
                                :required="true"
                                col="12"
                                autofocus
                            />
                        </div>

                        <div class="row">
                            <x-form.textfield 
                                name="password" 
                                label='<i class="bi bi-lock me-1"></i>Password'
                                type="password"
                                input_type="password"
                                placeholder="Enter your password"
                                :required="true"
                                col="12"
                            />
                        </div>

                        <div class="mb-3 form-check">
                            <input 
                                type="checkbox" 
                                class="form-check-input" 
                                id="remember" 
                                name="remember"
                            >
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </button>
                        </div>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
</body>
</html>
