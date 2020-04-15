<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;


    protected function setUp(): void
    {
        parent::setUp(); //

        $user = factory(User::class)->create();

        $this->actingAs($user);

    }

    public function testGetLocandina()
    {

        $filename = 'test_image.jpg';
        Storage::put('public/locandine/'. $filename,'null');

        $response = $this->get(route('img.locandina',$filename));

        $response->assertStatus(200);

        Storage::delete('public/locandine/'. $filename);

    }


    public function testGetLocandinaPlaceholder()
    {


        $response = $this->get(route('img.locandina'));

        $response->assertStatus(200);

    }


    public function testGetLocandinaIfFileDoesNotExist()
    {

        $response = $this->get(route('img.locandina','not-exists.jpg'));

        // Must return default image (placeholder.jpg)
        $response->assertStatus(200);


    }
}
