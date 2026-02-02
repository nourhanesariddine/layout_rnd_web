@props(['headers' => [], 'responsive' => true, 'hover' => true, 'striped' => false, 'class' => ''])

@php
    $tableClasses = 'table';
    if ($hover) $tableClasses .= ' table-hover';
    if ($striped) $tableClasses .= ' table-striped';
    $tableClasses .= ' ' . $class;
@endphp

@if($responsive)
    <div class="table-responsive">
@endif
        <table class="{{ trim($tableClasses) }}">
            @if(!empty($headers))
                <thead>
                    <tr>
                        @foreach($headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
@if($responsive)
    </div>
@endif
