

@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label']) }}>
    <i class="fas fa-envelope text-primary-600 mr-2"></i>
    {{ $value ?? $slot }}
</label>
