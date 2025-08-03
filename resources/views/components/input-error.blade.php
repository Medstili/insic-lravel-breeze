@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600']) }}>
        
        @foreach ((array) $messages as $message)
            <li> <i class="fas fa-exclamation-circle"></i> {{ $message }}</li>
        @endforeach
    </ul>
@endif
