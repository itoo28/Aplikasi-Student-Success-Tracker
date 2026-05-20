@props([
    'disabled' => false,
    'type' => 'text',
])

<flux:input :type="$type" :disabled="$disabled" {{ $attributes->class('w-full') }} />
