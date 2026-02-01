<div class="flex items-center gap-4">
    <label class="flex items-center gap-2 cursor-pointer">
        <flux:checkbox
            wire:model.live="attended"
            wire:change="toggleAttendance"
        />
        <span class="text-sm">I played this gig</span>
    </label>

    @if($attendanceCount > 0)
        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ $attendanceCount }} {{ Str::plural('person', $attendanceCount) }} attended
        </div>
    @endif
</div>
