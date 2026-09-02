<?php

use App\Models\User;
use App\Notifications\AdminNewUserRegistered;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

test('verified users pending admin approval are redirected to pending approval notice', function () {
    $user = User::factory()->pendingAdminApproval()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('approval.notice', absolute: false));
});

test('pending approval notice can be rendered for waiting users', function () {
    $user = User::factory()->pendingAdminApproval()->create();

    $this->actingAs($user)
        ->get(route('approval.notice'))
        ->assertOk()
        ->assertSee('waiting for admin approval', false);
});

test('approved users are redirected away from pending approval notice', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('approval.notice'))
        ->assertRedirect(route('dashboard', absolute: false));
});

test('admin email users can access member area without manual admin approval', function () {
    config(['admin.emails' => ['admin@example.com']]);

    $user = User::factory()->pendingAdminApproval()->create([
        'email' => 'admin@example.com',
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

test('admin can verify pending users from signed verification link', function () {
    config(['admin.emails' => ['admin@example.com']]);

    $admin = User::factory()->pendingAdminApproval()->create([
        'email' => 'admin@example.com',
    ]);

    $pendingUser = User::factory()->pendingAdminApproval()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'admin.users.verify',
        now()->addHour(),
        ['user' => $pendingUser->id],
    );

    $this->actingAs($admin)
        ->get($verificationUrl)
        ->assertRedirect(route('settings.users', absolute: false));

    expect($pendingUser->fresh()->verified_at)->not->toBeNull();
});

test('non-admin users can not verify pending users from signed link', function () {
    config(['admin.emails' => ['admin@example.com']]);

    $user = User::factory()->create([
        'email' => 'member@example.com',
    ]);

    $pendingUser = User::factory()->pendingAdminApproval()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'admin.users.verify',
        now()->addHour(),
        ['user' => $pendingUser->id],
    );

    $this->actingAs($user)
        ->get($verificationUrl)
        ->assertForbidden();

    expect($pendingUser->fresh()->verified_at)->toBeNull();
});

test('admin user verification settings page is admin-only', function () {
    config(['admin.emails' => ['admin@example.com']]);

    $member = User::factory()->create();
    $admin = User::factory()->pendingAdminApproval()->create([
        'email' => 'admin@example.com',
    ]);

    $this->actingAs($member)
        ->get(route('settings.users'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('settings.users'))
        ->assertOk();
});

test('new registration notifies configured admin emails', function () {
    config(['admin.emails' => ['admin@example.com', 'bandleader@example.com']]);

    Notification::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'New Member',
        'email' => 'fresh@example.com',
        'instrument' => 'Trumpet',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    Notification::assertSentOnDemand(AdminNewUserRegistered::class, function (AdminNewUserRegistered $notification, array $channels, object $notifiable): bool {
        $mailRoutes = (array) ($notifiable->routes['mail'] ?? []);

        return $notification->user->email === 'fresh@example.com'
            && $channels === ['mail']
            && in_array('admin@example.com', $mailRoutes, true)
            && in_array('bandleader@example.com', $mailRoutes, true);
    });
});

test('registration auto-approves configured admin emails', function () {
    config(['admin.emails' => ['admin@example.com']]);

    $response = $this->post(route('register.store'), [
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'instrument' => 'Trombone',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $user = User::query()->where('email', 'admin@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user?->verified_at)->not->toBeNull();
});
