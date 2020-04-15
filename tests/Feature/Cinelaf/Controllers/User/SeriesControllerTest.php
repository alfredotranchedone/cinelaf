<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Cinelaf\Models\Film;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeriesControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    private $user;

    protected function setUp(): void
    {
        parent::setUp(); //

        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->user = $user;

    }

    public function testGetIndex()
    {

        $response = $this->get(route('series.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.film.index');

    }

    public function testGetUserRated()
    {

        $response = $this->get(route('series.myratings'));

        $response->assertStatus(200);
        $response->assertViewIs('user.film.myratings');
    }

    public function testGetUserNotRated()
    {

        $response = $this->get(route('series.mynotrated'));

        $response->assertStatus(200);
        $response->assertViewIs('user.film.mynotrated');
    }


    public function testGetUserNoQuorum()
    {
        $response = $this->get(route('series.noquorum'));

        $response->assertStatus(200);
        $response->assertViewIs('user.film.noquorum');
    }

}
