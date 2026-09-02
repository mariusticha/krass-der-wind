<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class UserVerification extends Component
{
    use WithPagination;

    public string $search = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        abort_unless(Auth::user()->can('admin'), 403);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function verifyUser(int $userId): void
    {
        abort_unless(Auth::user()->can('admin'), 403);

        $user = User::query()->findOrFail($userId);

        $user->markAsAdminVerified();

        session()->flash('status', 'user-verified');
    }

    public function render(): Factory | View
    {
        return view('livewire.settings.user-verification', [
            'pendingUsers' => User::query()
                ->pendingAdminVerification()
                ->when(
                    $this->search !== '',
                    fn($query) => $query->where(function ($builder): void {
                        $builder
                            ->where('name', 'like', "%{$this->search}%")
                            ->orWhere('email', 'like', "%{$this->search}%");
                    }),
                )
                ->orderByDesc('email_verified_at')
                ->orderBy('name')
                ->paginate(10),
        ])->layoutData([
            'titleAddition' => __('User Verification'),
        ]);
    }
}
