<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIncorrectRoute()
    {
        $response = $this->get('/incorrect876543456');

        $response->assertNotFound();
    }

    public function testRouteLogin()
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function testRouteRegister()
    {
        $response = $this->get('/register');

        $response->assertOk();
    }
}
