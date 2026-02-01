<div class="flex items-center gap-4">
    <div class="flex gap-2">
        <flux:button
            wire:click="rsvp('yes')"
            size="sm"
            :variant="$rsvpStatus === 'yes' ? 'primary' : 'ghost'"
        >
            ✓ Yes
        </flux:button>
        <flux:button
            wire:click="rsvp('maybe')"
            size="sm"
            :variant="$rsvpStatus === 'maybe' ? 'primary' : 'ghost'"
        >
            ? Maybe
        </flux:button>
        <flux:button
            wire:click="rsvp('no')"
            size="sm"
            :variant="$rsvpStatus === 'no' ? 'primary' : 'ghost'"
            color="red"
        >
            ✗ No
        </flux:button>
    </div>

    @if($rsvpCount > 0)
        <div class="text-sm text-gray-600">
            {{ $rsvpCount }} {{ Str::plural('person', $rsvpCount) }} responded
        </div>
    @endif
</div>
