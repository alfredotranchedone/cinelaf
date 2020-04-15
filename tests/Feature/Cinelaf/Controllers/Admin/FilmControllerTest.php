<?php

namespace Tests\Feature\Cinelaf\Controllers\Admin;

use App\User;
use Cinelaf\Models\Film;
use Cinelaf\Models\Rating;
use Cinelaf\Models\Regista;
use Cinelaf\Models\Watchlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilmControllerTest extends TestCase
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

    public function testGetEdit()
    {

        $film = factory(Film::class)->create();

        $response = $this->get(route('admin.film.edit',$film->id));

        $response->assertViewIs('admin.film.edit');
    }

    public function testPutUpdate()
    {

        Storage::fake('local');

        $film = factory(Film::class)->create();
        $regista = factory(Regista::class)->create();
        $film->regista()->attach($regista->id);

        $dataToSend = [
            'titolo' => $this->faker->sentence(5),
            'anno' => $this->faker->year,
            'locandina' => UploadedFile::fake()->image('test_img.jpg',500,500),
            'registi' => [$regista->id]
        ];

        $response = $this->put(route('admin.film.edit',$film->id),$dataToSend);

        $response->assertRedirect(route('film.show',[$film->id]));

    }

    public function testDelete()
    {

        Storage::fake('local');
        $locandina = UploadedFile::fake()->image('test_img.jpg',500,500);

        $film = factory(Film::class)->create(['locandina' => $locandina->name]);
        $regista = factory(Regista::class)->create();
        $film->regista()->attach($regista->id);
        $film->rating()->save(new Rating([
            'user_id' => $this->admin->id,
            'voto' => 4.0
        ]));
        $film->watchlist()->save(new Watchlist([
            'user_id' => $this->admin->id,
            'film_id' => $film->id
        ]));

        /* Check file exists */
        $this->assertFileExists($locandina->path());
        /* Check records exist */
        $this->assertNotNull($film);
        $this->assertNotNull($film->regista);
        $this->assertNotNull($film->rating);
        $this->assertNotNull($film->watchlist);

        /* Call route */
        $response = $this->delete(route('admin.film.delete',$film->id));

        /* TODO Check file is deleted in UploadServiceTest */

        /* Check deleted_at */
        $this->assertSoftDeleted('films',['id'=>$film->id]);

        /* Check related data */
        $this->assertDatabaseMissing('films_registi', ['film_id'=>$film->id,'regista_id' => $regista->id]);
        $this->assertDatabaseMissing('ratings', ['film_id'=>$film->id, 'user_id' => $this->admin->id]);
        $this->assertDatabaseMissing('watchlists', ['film_id'=>$film->id, 'user_id' => $this->admin->id]);

    }


}
