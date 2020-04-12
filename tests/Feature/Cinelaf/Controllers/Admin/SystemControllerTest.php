<?php

namespace Tests\Feature\Cinelaf\Controllers\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SystemControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp(); //

        $user = new User();
        $user->name = $this->faker->name;
        $user->email = $this->faker->safeEmail;
        $user->password = $this->faker->password(8);
        $user->is_super_admin = 1;
        $user->save();

        $this->user = $user;

    }


    /**
     *
     * @return void
     */
    public function testAdminCanReset()
    {

        $this->actingAs($this->user);

        $response = $this->get(route('admin.system.reset'));

        $response
            ->assertSeeText('Reset Done');
        $response->assertStatus(200);


    }
}
