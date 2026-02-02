@props(['name', 'label' => null, 'value' => '', 'required' => false, 'class' => '', 'col' => null])

@php
    $config = [
        'name' => $name,
        'label' => $label,
        'type' => 'datefield',
        'value' => $value,
        'required' => $required,
        'class' => $class,
        'column' => $col,
    ];
    $field = form_field_config($config);
@endphp

<div class="{{ $field['colClass'] }} {{ $field['classes']['field_wrapper'] }}">
    <label for="{{ $field['inputId'] }}" class="{{ $field['classes']['label'] }}">
        {{ $field['label'] }}
        @if($field['required'])
            <span class="{{ $field['classes']['required'] }}">*</span>
        @endif
    </label>
    <input 
        type="date" 
        class="{{ $field['classes']['input'] }} {{ $field['errorClass'] }} {{ $field['class'] }}" 
        id="{{ $field['inputId'] }}" 
        name="{{ $field['name'] }}" 
        value="{{ $field['oldValue'] }}"
        @if($field['required']) required @endif
        {{ $attributes }}
    >
    @error($field['name'])
        <div class="{{ $field['classes']['error'] }}">{{ $message }}</div>
    @enderror
</div>
