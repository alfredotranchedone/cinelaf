<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Cinelaf\Repositories\Registi;
use Cinelaf\Services\FilmSession;
use Cinelaf\Services\Upload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilmControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * @return void
     * @test
     */
    public function add_film()
    {

        Storage::fake('local');

        $filmSession = new FilmSession();
        $user = factory(User::class)->create();

        $titolo = $this->faker->sentence();
        $regista = array($this->faker->numberBetween(1,10));
        $type = $this->faker->numberBetween(1,2);
        $anno = $this->faker->year;
        $locandina = UploadedFile::fake()->image('test_img.jpg',500,500);

        $response = $this->actingAs($user);

        /* Step 1 */
        $response
            ->get(route('film.add'))
            ->assertStatus(200);

        /* Step 2 */
        $response
            ->post(route('film.add.step_2'),[
                'titolo' => $titolo
            ])
            ->assertStatus(200);

        // Check $filmsession for $titolo
        $this->assertEqualsIgnoringCase($titolo, $filmSession->get()['titolo']);

        /* Step 3 */
        $response
            ->post(route('film.add.step_3'),[
                'regista' => $regista,
                'type' => $type
            ])
            ->assertStatus(200);

        // Check $filmsession for $regista, $type
        $this->assertEqualsIgnoringCase($regista, $filmSession->get()['regista']);
        $this->assertEqualsIgnoringCase($type, $filmSession->get()['type']);

        // dump($filmSession->get());

        /* Save */
        $response
            ->post(route('film.create'),[
                'anno' => $anno,
                'locandina' => $locandina
            ])
            ->assertRedirect(route('home'));

    }


}
