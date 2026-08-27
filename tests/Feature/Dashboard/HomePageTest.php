<?php

use App\Models\User;

test('dashboard home page is displayed', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('dashboard.index'))
        ->assertOk()
        ->assertSee('نظرة عامة على المنتجات')
        ->assertSee('التصنيفات والشركات')
        ->assertSee('الأخبار')
        ->assertSee('أبرز الشركات المصنعة');
});
