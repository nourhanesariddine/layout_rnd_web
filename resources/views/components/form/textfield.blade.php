@props(['name', 'label' => null, 'type' => 'text', 'value' => '', 'required' => false, 'placeholder' => '', 'class' => '', 'col' => null, 'input_type' => null])

@php
    $config = [
        'name' => $name,
        'label' => $label,
        'type' => 'textfield',
        'input_type' => $input_type ?? $type,
        'value' => $value,
        'required' => $required,
        'placeholder' => $placeholder,
        'class' => $class,
        'column' => $col,
    ];
    $field = form_field_config($config);
@endphp

<div class="{{ $field['colClass'] }} {{ $field['classes']['field_wrapper'] }}">
    <label for="{{ $field['inputId'] }}" class="{{ $field['classes']['label'] }}">
        {!! $field['label'] !!}
        @if($field['required'])
            <span class="{{ $field['classes']['required'] }}">*</span>
        @endif
    </label>
    <input 
        type="{{ $field['inputType'] }}" 
        class="{{ $field['classes']['input'] }} {{ $field['errorClass'] }} {{ $field['class'] }}" 
        id="{{ $field['inputId'] }}" 
        name="{{ $field['name'] }}" 
        value="{{ $field['oldValue'] }}"
        @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
        @if($field['required']) required @endif
        {{ $attributes }}
    >
    @error($field['name'])
        <div class="{{ $field['classes']['error'] }}">{{ $message }}</div>
    @enderror
</div>
