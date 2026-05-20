@props(['for'])

@error($for)
    <flux:text color="red" size="sm" {{ $attributes }}>
        {{ $message }}
    </flux:text>
@enderror
