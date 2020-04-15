<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
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

    public function testGetUserProfile()
    {

        $response = $this->get(route('me'));

        $response->assertViewIs('user.edit');

    }

    public function testEditUserProfile()
    {

        $response = $this->put(route('me.save',$this->user->id),[
            'name' => 'edited',
            'email' => 'edited@edited.etd'
        ]);

        $response->assertSessionMissing('errors');

        $response->assertRedirect(route('me'));
        $this->assertDatabaseHas('users',[
            'name' => 'edited',
            'email' => 'edited@edited.etd'
        ]);

    }

    public function testCantEditAnotherUserProfile()
    {

        $another_user = factory(User::class)->create();

        $response = $this->put(route('me.save',$another_user->id),[
            'name' => 'edited',
            'email' => 'edited@edited.etd'
        ]);

        $response->assertStatus(401);

    }


}
