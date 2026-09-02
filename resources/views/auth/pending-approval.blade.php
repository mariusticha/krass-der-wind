<x-layouts::auth>
    <div class="mt-4 flex flex-col gap-6">
        <flux:text class="text-center">
            {{ __('Your email has been verified.') }}
        </flux:text>

        <flux:text class="text-center">
            {{ __('Your account is waiting for admin approval before you can access the member area.') }}
        </flux:text>

        <flux:text class="text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('You will be able to continue as soon as an admin verifies your account.') }}
        </flux:text>

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer" data-test="logout-button">
                    {{ __('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
