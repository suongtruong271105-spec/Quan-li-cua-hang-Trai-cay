<?php

namespace Tests\Feature;

use Tests\TestCase;

class WebViewsTest extends TestCase
{
    public function test_frontend_pages_share_the_auth_navigation_layout(): void
    {
        foreach ([
            '/',
            '/login',
            '/register',
            '/fruits',
            '/my-orders',
            '/inventory',
            '/orders',
            '/admin/users',
        ] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('js/auth.js')
                ->assertSee('data-auth-roles="admin"', false);
        }
    }
}
