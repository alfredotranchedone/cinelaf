<?php

namespace Tests\Feature\Cinelaf\Controllers\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SystemControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp(); //

        $user = new User();
        $user->name = $this->faker->name;
        $user->email = $this->faker->safeEmail;
        $user->password = $this->faker->password(8);
        $user->is_super_admin = 1;
        $user->save();

        $this->admin = $user;

    }



    public function testUserCantReset()
    {
        $user=factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.system.reset'));
        $response->assertStatus(302);

    }


}
