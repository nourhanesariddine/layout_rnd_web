@extends('layouts.app')

@section('title', 'Edit Contact')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <x-card title='<i class="bi bi-pencil me-2"></i>Edit Contact'>
            <form action="{{ route('contacts.update', $contact) }}" method="POST" id="contactForm">
                @csrf
                @method('PUT')
                
                @php
                    $fields = config('form-fields.contact');
                @endphp
                
                <div class="row">
                    <x-form.textfield 
                        name="{{ $fields['first_name']['name'] }}" 
                        label="{{ $fields['first_name']['label'] }}" 
                        :value="$contact->first_name"
                        :required="$fields['first_name']['required']" 
                        col="{{ $fields['first_name']['column'] }}" 
                    />
                    <x-form.textfield 
                        name="{{ $fields['last_name']['name'] }}" 
                        label="{{ $fields['last_name']['label'] }}" 
                        :value="$contact->last_name"
                        :required="$fields['last_name']['required']" 
                        col="{{ $fields['last_name']['column'] }}" 
                    />
                </div>
                
                <div class="row">
                    <x-form.textfield 
                        name="{{ $fields['phone']['name'] }}" 
                        label="{{ $fields['phone']['label'] }}" 
                        input_type="{{ $fields['phone']['input_type'] }}" 
                        :value="$contact->phone"
                        :required="$fields['phone']['required']" 
                        col="{{ $fields['phone']['column'] }}" 
                    />
                    <x-form.datefield 
                        name="{{ $fields['birthdate']['name'] }}" 
                        label="{{ $fields['birthdate']['label'] }}" 
                        :value="$contact->birthdate"
                        :required="$fields['birthdate']['required']" 
                        col="{{ $fields['birthdate']['column'] }}" 
                    />
                </div>
                
                <div class="row">
                    <x-form.textfield 
                        name="{{ $fields['city']['name'] }}" 
                        label="{{ $fields['city']['label'] }}" 
                        :value="$contact->city"
                        :required="$fields['city']['required']" 
                        col="{{ $fields['city']['column'] }}" 
                    />
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Contact
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

