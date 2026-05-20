<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="rounded-2xl border border-zinc-200/70 bg-white px-4 py-5 shadow-sm sm:p-6">
            {{ $content }}
        </div>
    </div>
</div>
