<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    public function testGetRatings()
    {

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $response = $this->get(route('user.ratings',$user->id));

        $response->assertStatus(200);

    }

}
