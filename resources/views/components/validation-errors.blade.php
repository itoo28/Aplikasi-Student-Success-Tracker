@if ($errors->any())
    <div {{ $attributes->class('rounded-xl border border-rose-200 bg-rose-50 p-4') }}>
        <flux:heading class="text-rose-700">{{ __('Whoops! Something went wrong.') }}</flux:heading>

        <ul class="mt-3 list-disc list-inside text-sm text-rose-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
