@extends('layouts.app')

@section('title', 'Add New Contact')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <x-card title='<i class="bi bi-person-plus me-2"></i>Add New Contact'>
            <form action="{{ route('contacts.store') }}" method="POST" id="contactForm">
                @csrf
                
                @php
                    $fields = config('form-fields.contact');
                @endphp
                
                <div class="row">
                    <x-form.textfield 
                        name="{{ $fields['first_name']['name'] }}" 
                        label="{{ $fields['first_name']['label'] }}" 
                        :required="$fields['first_name']['required']" 
                        col="{{ $fields['first_name']['column'] }}" 
                    />
                    <x-form.textfield 
                        name="{{ $fields['last_name']['name'] }}" 
                        label="{{ $fields['last_name']['label'] }}" 
                        :required="$fields['last_name']['required']" 
                        col="{{ $fields['last_name']['column'] }}" 
                    />
                </div>
                
                <div class="row">
                    <x-form.textfield 
                        name="{{ $fields['phone']['name'] }}" 
                        label="{{ $fields['phone']['label'] }}" 
                        input_type="{{ $fields['phone']['input_type'] }}" 
                        :required="$fields['phone']['required']" 
                        col="{{ $fields['phone']['column'] }}" 
                    />
                    <x-form.datefield 
                        name="{{ $fields['birthdate']['name'] }}" 
                        label="{{ $fields['birthdate']['label'] }}" 
                        :required="$fields['birthdate']['required']" 
                        col="{{ $fields['birthdate']['column'] }}" 
                    />
                </div>
                
                <div class="row">
                    <x-form.textfield 
                        name="{{ $fields['city']['name'] }}" 
                        label="{{ $fields['city']['label'] }}" 
                        :required="$fields['city']['required']" 
                        col="{{ $fields['city']['column'] }}" 
                    />
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Contact
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

