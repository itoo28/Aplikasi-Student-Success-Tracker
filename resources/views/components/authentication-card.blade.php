<div class="mx-auto flex min-h-screen w-full max-w-md items-center px-5 py-10">
    <div class="w-full">
        <div class="mb-6 flex justify-center">
            {{ $logo }}
        </div>

        <div class="rounded-3xl border border-zinc-200/70 bg-white/95 p-7 shadow-xl shadow-zinc-200/50 backdrop-blur">
            {{ $slot }}
        </div>
    </div>
</div>
