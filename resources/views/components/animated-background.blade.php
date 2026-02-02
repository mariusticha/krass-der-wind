<!-- Animated background elements -->
<div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-20 right-20 w-96 h-96 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-40 left-20 w-[500px] h-[500px] bg-gradient-to-tr from-orange-500/8 to-amber-500/8 rounded-full blur-3xl animate-pulse" style="animation-delay: 1.5s;"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-gradient-to-r from-amber-400/5 to-orange-400/5 rounded-full blur-3xl animate-spin" style="animation-duration: 40s;"></div>

    <!-- Floating musical notes -->
    <div class="absolute top-32 left-1/4 text-amber-500/15 animate-bounce" style="animation-duration: 4s;">
        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
        </svg>
    </div>
    <div class="absolute top-1/3 right-1/4 text-orange-500/12 animate-bounce" style="animation-duration: 5s; animation-delay: 1s;">
        <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
        </svg>
    </div>

    <x-floating-rings />
</div>
