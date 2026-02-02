@extends('layouts.app')

@section('title', 'Add New Department')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <x-card title='<i class="bi bi-building me-2"></i>Add New Department'>
            <form action="{{ route('departments.store') }}" method="POST" id="departmentForm">
                @csrf

                @php
                    $fields = config('form-fields.department');
                @endphp

                <div class="row">
                    <x-form.textfield
                        name="{{ $fields['name']['name'] }}"
                        label="{{ $fields['name']['label'] }}"
                        :required="$fields['name']['required']"
                        col="{{ $fields['name']['column'] }}"
                    />
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Department
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection
