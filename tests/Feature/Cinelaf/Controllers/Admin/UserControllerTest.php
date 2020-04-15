<?php

namespace Tests\Feature\Cinelaf\Controllers\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserControllerTest extends TestCase
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

        $this->actingAs($user);

    }


    public function testGetIndex()
    {

        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');

    }


    public function testGetAdd()
    {

        $response = $this->get(route('admin.users.add'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.add');

    }

    public function testPostCreateUser()
    {

        $name = $this->faker->name;
        $email = $this->faker->email;
        $pwd = $this->faker->password(6,14);

        $response = $this->post(route('admin.users.add'),[
            'name' => $name,
            'email' => $email,
            'password' => $pwd,
            'password_confirmation' => $pwd,
            'admin' => 0
        ]);

        $response->assertSessionMissing('errors');

        $this->assertDatabaseHas('users',['email' => $email]);
        $user = User::where('email', $email)->first();
        $this->assertFalse($user->isSuperAdmin());

    }


    public function testPostCreateAdmin()
    {

        $name = $this->faker->name;
        $email = $this->faker->email;
        $pwd = $this->faker->password(6,14);

        $response = $this->post(route('admin.users.add'),[
            'name' => $name,
            'email' => $email,
            'password' => $pwd,
            'password_confirmation' => $pwd,
            'admin' => 1
        ]);

        $response->assertSessionMissing('errors');

        $this->assertDatabaseHas('users',['email' => $email]);
        $user = User::where('email', $email)->first();
        $this->assertTrue($user->isSuperAdmin());

    }

    public function testGetEdit()
    {
        $user=factory(User::class)->create();
        $response = $this->get(route('admin.users.edit',$user->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');

    }

    public function testPutUpdateUser()
    {

        $new_name = $this->faker->name;
        $new_pwd = $this->faker->password(6,14);

        $user=factory(User::class)->create();
        $response = $this->put(route('admin.users.edit', $user->id),[
            'name' => $new_name,
            'email'=>$user->email
        ]);

        $response->assertSessionMissing('errors');

        $this->assertDatabaseHas('users',['id' => $user->id]);
        $user = User::where('id', $user->id)->first();
        $this->assertEquals($user->name,$new_name);
        $this->assertFalse($user->isSuperAdmin());

    }


    public function testPutUpdateAdmin()
    {

        $new_name = $this->faker->name;
        $new_pwd = $this->faker->password(6,14);

        $user=factory(User::class)->create();
        $response = $this->put(route('admin.users.edit', $user->id),[
            'name' => $new_name,
            'email'=>$user->email,
            'admin' => 1
        ]);

        $response->assertSessionMissing('errors');

        $this->assertDatabaseHas('users',['id' => $user->id]);

        $user = User::where('id', $user->id)->first();
        $this->assertEquals($user->name,$new_name);
        $this->assertTrue($user->isSuperAdmin());

    }

    public function testDeleteUser()
    {

        $user=factory(User::class)->create();
        $response = $this->delete(route('admin.users.delete', $user->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users',['id' => $user->id]);

    }

    public function testDeleteUserNotExistent()
    {

        $user=factory(User::class)->create();
        $response = $this->delete(route('admin.users.delete', 99));
        $this->assertDatabaseHas('users',['id' => $user->id]);

    }


}
