<?php

use App\Models\User;

it('redirects a signed-in customer to their dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('customer.dashboard'));
});

it('redirects a signed-in vendor to their dashboard', function () {
    $user = User::factory()->create();
    $user->syncRoles(['vendor']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('vendor.dashboard'));
});

it('redirects a signed-in super admin to the admin dashboard', function () {
    $user = User::factory()->create();
    $user->syncRoles(['super_admin']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('admin.dashboard'));
});

it('redirects a signed-in user without any role to profile settings', function () {
    $user = User::factory()->create();
    // Factory auto-assigns "customer" via User::booted(); remove it to simulate a role-less user.
    $user->syncRoles([]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('profile.edit'));
});

it('redirects guests to login', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});