<section class="w-full">
    @include('components.settings.heading')

    <flux:heading class="sr-only">{{ __('User Verification Settings') }}</flux:heading>

    <x-settings.layout :heading="__('User Verification')" :subheading="__('Review and approve members waiting for admin verification')">
        <div class="space-y-5">
            <flux:input wire:model.live.debounce.300ms="search" :label="__('Search Users')" type="text"
                :placeholder="__('Search by name or email')" />

            @if (session('status') === 'user-verified')
                <flux:callout variant="success" icon="check-circle" :heading="__('User verified successfully.')" />
            @endif

            @if (session('status') === 'user-already-verified')
                <flux:callout variant="info" icon="information-circle" :heading="__('This user is already verified.')" />
            @endif

            @if ($pendingUsers->isEmpty())
                <flux:card class="p-5">
                    <flux:text>{{ __('There are no pending users to verify right now.') }}</flux:text>
                </flux:card>
            @else
                <div class="space-y-3">
                    @foreach ($pendingUsers as $pendingUser)
                        <flux:card class="p-4" wire:key="pending-user-{{ $pendingUser->id }}">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <flux:heading size="sm">{{ $pendingUser->name }}</flux:heading>
                                    <flux:text class="mt-1">{{ $pendingUser->email }}</flux:text>

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        @if ($pendingUser->hasVerifiedEmail())
                                            <flux:badge color="green">{{ __('Email verified') }}</flux:badge>
                                        @else
                                            <flux:badge color="amber">{{ __('Email not verified') }}</flux:badge>
                                        @endif
                                    </div>
                                </div>

                                <div class="sm:text-right">
                                    <flux:button variant="primary" wire:click="verifyUser({{ $pendingUser->id }})">
                                        {{ __('Verify user') }}
                                    </flux:button>
                                </div>
                            </div>
                        </flux:card>
                    @endforeach
                </div>

                <div>
                    {{ $pendingUsers->links() }}
                </div>
            @endif
        </div>
    </x-settings.layout>
</section>
