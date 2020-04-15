<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Cinelaf\Models\Film;
use Cinelaf\Models\Regista;
use Cinelaf\Services\FilmSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilmControllerTest extends TestCase
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

        $response = $this->get(route('film.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.film.index');

    }


    /**
     * @return void
     * @test
     */
    public function add_film()
    {

        Storage::fake('local');

        $filmSession = new FilmSession();

        $titolo = $this->faker->sentence();
        $regista = array($this->faker->numberBetween(1,10));
        $type = $this->faker->numberBetween(1,2);
        $anno = $this->faker->year;
        $locandina = UploadedFile::fake()->image('test_img.jpg',500,500);

        /* Step 1 */
        $response = $this
            ->get(route('film.add'))
            ->assertStatus(200);

        /* Step 2 */
        $response = $this
            ->post(route('film.add.step_2'),[
                'titolo' => $titolo
            ]);

        $response->assertStatus(200);

        // Check $filmsession for $titolo
        $this->assertEqualsIgnoringCase($titolo, $filmSession->get()['titolo']);

        /* Step 3 */
        $response = $this
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
        $response = $this
            ->post(route('film.create'),[
                'anno' => $anno,
                'locandina' => $locandina
            ]);

        $response->assertSessionMissing('errors');

        $response->assertRedirect(route('home'));

    }

    public function testShowFilm()
    {

        $film=factory(Film::class)->create(['user_id'=>$this->user->id]);
        $regista = factory(Regista::class)->create();
        $film->regista()->attach($regista->id);

        $response = $this->get(route('film.show',$film->id));

        $response->assertStatus(200);
        $response->assertViewIs('user.film.show');

    }


    public function testGetUserRated()
    {

        $response = $this->get(route('film.myratings'));

        $response->assertStatus(200);
        $response->assertViewIs('user.film.myratings');
    }

    public function testGetUserNotRated()
    {

        $response = $this->get(route('film.mynotrated'));

        $response->assertStatus(200);
        $response->assertViewIs('user.film.mynotrated');
    }


    public function testGetUserNoQuorum()
    {
        $response = $this->get(route('film.noquorum'));

        $response->assertStatus(200);
        $response->assertViewIs('user.film.noquorum');
    }


}
