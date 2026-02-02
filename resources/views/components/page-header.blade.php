@props(['title', 'description' => null])

<div class="mb-6 sm:mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div class="flex-1">
            <h1
                class="text-2xl min-h-[2.5rem] sm:text-3xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                {{ $title }}
            </h1>
            @if ($description)
                <p class="text-sm mt-2 sm:text-base text-zinc-600 dark:text-zinc-400 mt-1">{{ $description }}</p>
            @endif
        </div>

        @if (isset($actions))
            <div class="flex-shrink-0 ">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
