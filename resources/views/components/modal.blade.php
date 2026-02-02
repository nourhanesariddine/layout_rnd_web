@props(['id', 'title' => null, 'size' => null, 'footer' => null, 'class' => ''])

@php
    $modalSize = '';
    if ($size === 'sm') $modalSize = 'modal-sm';
    if ($size === 'lg') $modalSize = 'modal-lg';
    if ($size === 'xl') $modalSize = 'modal-xl';
@endphp

<div class="modal fade {{ $class }}" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }}">
        <div class="modal-content">
            @if($title)
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $id }}Label">
                        {!! $title !!}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="modal-body">
                {{ $slot }}
            </div>
            
            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
