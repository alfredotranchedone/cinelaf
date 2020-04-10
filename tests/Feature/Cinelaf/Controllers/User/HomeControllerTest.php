<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function index_returns_a_view()
    {

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);

        $this->assertAuthenticatedAs($user);

    }
}
