<?php

namespace Tests\Feature\Cinelaf\Controllers\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;



    /**
     *
     * @return void
     *
     * @test
     *
     */
    public function admin_can_access_admin_dashboard()
    {

        /* Create Admin */
        $user = new User();
        $user->name = $this->faker->name;
        $user->email = $this->faker->safeEmail;
        $user->password = $this->faker->password(8);
        $user->is_super_admin = 1;
        $user->save();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertViewIs('admin.dashboard');
        $this->assertAuthenticatedAs($user);

    }


    /**
     *
     * @return void
     *
     * @test
     *
     */
    public function user_cant_access_admin_dashboard()
    {

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertRedirect();

    }



}
