@props(['title', 'description' => null])

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">{{ $title }}</h1>
        @if($description)
            <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div>
            {{ $actions }}
        </div>
    @endif
</div>
