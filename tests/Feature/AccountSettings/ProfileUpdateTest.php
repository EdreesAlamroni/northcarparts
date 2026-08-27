<?php

use App\Models\User;
use Livewire\Livewire;

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('dashboard.account-settings.profile.edit'))
        ->assertOk()
        ->assertSee('إعدادات الحساب')
        ->assertSee('إدارة البيانات الشخصية وإعدادات الحساب')
        ->assertSee('الرئيسية');
});

test('account settings redirects to the profile page', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dashboard/account-settings')
        ->assertRedirect('/dashboard/account-settings/profile');
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::dashboard.account-settings.profile')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::dashboard.account-settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});
