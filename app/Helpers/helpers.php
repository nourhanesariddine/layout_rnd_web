<?php

use Carbon\Carbon;

if (!function_exists('form_field_config')) {
    /**
     * Process form field configuration using FormHelper
     *
     * @param array $config
     * @return array
     */
    function form_field_config(array $config): array
    {
        $classes = config('form-classes');

        // Extract configuration
        $name = $config['name'] ?? '';
        $label = $config['label'] ?? ucfirst(str_replace('_', ' ', $name));
        $fieldType = $config['type'] ?? 'textfield'; // textfield, datefield, filefield
        $inputType = $config['input_type'] ?? 'text'; // text, tel, email, etc.
        $value = $config['value'] ?? '';
        $required = $config['required'] ?? false;
        $placeholder = $config['placeholder'] ?? '';
        $accept = $config['accept'] ?? '';
        $class = $config['class'] ?? '';
        $col = $config['column'] ?? $config['col'] ?? null;

        $inputId = $name;
        $oldValue = old($name, $value ?? '');

        // Check for errors - will be handled by @error directive in Blade
        $errorClass = '';
        if (session()->has('errors')) {
            $errors = session()->get('errors');
            if ($errors && $errors->has($name)) {
                $errorClass = $classes['input_error'];
            }
        }

        $colClass = $col ? $classes['column_prefix'] . $col : '';

        // Handle date formatting for datefield
        if ($fieldType === 'datefield' && $oldValue) {
            if ($oldValue instanceof Carbon) {
                $oldValue = $oldValue->format('Y-m-d');
            } elseif (is_string($oldValue)) {
                try {
                    $oldValue = Carbon::parse($oldValue)->format('Y-m-d');
                } catch (\Exception $e) {
                    $oldValue = '';
                }
            }
        }

        return [
            'name' => $name,
            'label' => $label,
            'fieldType' => $fieldType,
            'inputType' => $inputType,
            'oldValue' => $oldValue,
            'required' => $required,
            'placeholder' => $placeholder,
            'accept' => $accept,
            'class' => $class,
            'colClass' => $colClass,
            'errorClass' => $errorClass,
            'inputId' => $inputId,
            'classes' => $classes,
        ];
    }
}
