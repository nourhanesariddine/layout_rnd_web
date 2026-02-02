@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <x-card title='<i class="bi bi-pencil me-2"></i>Edit User'>
            <form action="{{ route('users.update', $user) }}" method="POST" id="userForm">
                @csrf
                @method('PUT')

                @php
                    $fields = config('form-fields.user');
                @endphp

                <div class="row">
                    <x-form.textfield
                        name="{{ $fields['name']['name'] }}"
                        label="{{ $fields['name']['label'] }}"
                        :value="$user->name"
                        :required="$fields['name']['required']"
                        col="{{ $fields['name']['column'] }}"
                    />
                </div>

                <div class="row">
                    <x-form.textfield
                        name="{{ $fields['email']['name'] }}"
                        label="{{ $fields['email']['label'] }}"
                        input_type="{{ $fields['email']['input_type'] }}"
                        :value="$user->email"
                        :required="$fields['email']['required']"
                        col="{{ $fields['email']['column'] }}"
                    />
                </div>

                <div class="row">
                    <x-form.textfield
                        name="{{ $fields['password']['name'] }}"
                        label="New Password (leave blank to keep current)"
                        input_type="{{ $fields['password']['input_type'] }}"
                        :required="false"
                        col="{{ $fields['password']['column'] }}"
                    />
                    <x-form.textfield
                        name="{{ $fields['password_confirmation']['name'] }}"
                        label="Confirm New Password"
                        input_type="{{ $fields['password_confirmation']['input_type'] }}"
                        :required="false"
                        col="{{ $fields['password_confirmation']['column'] }}"
                    />
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update User
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection
