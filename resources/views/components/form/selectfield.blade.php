@props(['name', 'label' => null, 'value' => '', 'required' => false, 'options' => [], 'class' => '', 'col' => null, 'multiple' => false])

@php
    $config = [
        'name' => $name,
        'label' => $label,
        'type' => 'selectfield',
        'value' => $value,
        'required' => $required,
        'class' => $class,
        'column' => $col,
    ];
    $field = form_field_config($config);
    
    if ($multiple) {
        $oldValue = old($name, []);
        $selectedValues = !empty($oldValue) ? $oldValue : (is_array($value) ? $value : []);
    } else {
        $oldValue = old($name, $value);
        $selectedValues = is_array($oldValue) ? $oldValue : [$oldValue];
    }
@endphp

<div class="{{ $field['colClass'] }} {{ $field['classes']['field_wrapper'] }}">
    <label for="{{ $field['inputId'] }}" class="{{ $field['classes']['label'] }}">
        {!! $field['label'] !!}
        @if($field['required'])
            <span class="{{ $field['classes']['required'] }}">*</span>
        @endif
    </label>
    <select 
        class="{{ $field['classes']['input'] }} {{ $field['errorClass'] }} {{ $field['class'] }}" 
        id="{{ $field['inputId'] }}" 
        name="{{ $field['name'] }}{{ $multiple ? '[]' : '' }}"
        @if($field['required']) required @endif
        @if($multiple) multiple @endif
        {{ $attributes }}
    >
        @if(!$required && !$multiple)
            <option value="">Select {{ $label }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" 
                @if(in_array((string)$optionValue, array_map('strval', $selectedValues))) selected @endif
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @error($field['name'])
        <div class="{{ $field['classes']['error'] }}">{{ $message }}</div>
    @enderror
</div>
