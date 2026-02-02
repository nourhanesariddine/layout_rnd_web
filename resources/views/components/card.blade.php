@props(['title' => null, 'subtitle' => null, 'footer' => null, 'headerActions' => null, 'class' => ''])

<div class="card {{ $class }}">
    @if($title || $subtitle || $headerActions)
        <div class="card-header {{ $headerActions ? 'd-flex justify-content-between align-items-center' : '' }}">
            <div>
                @if($title)
                    <h5 class="card-title mb-0">{!! $title !!}</h5>
                @endif
                @if($subtitle)
                    <p class="card-subtitle text-muted mb-0">{{ $subtitle }}</p>
                @endif
            </div>
            @if($headerActions)
                <div>
                    {!! $headerActions !!}
                </div>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="card-footer">
            {!! $footer !!}
        </div>
    @endif
</div>
