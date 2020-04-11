<?php

namespace Tests\Feature\Cinelaf\Repositories;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class FilmTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     *
     * @return void
     * @test
     */
    public function save_film()
    {

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $filmRepo = new \Cinelaf\Repositories\Film();

        $titolo = $this->faker->sentence();
        $type = $this->faker->numberBetween(1,2);
        $anno = $this->faker->year;
        $locandina = Str::slug($titolo) . '.jpg';

        $film = $filmRepo->save($titolo,$anno,$locandina,$type);

        $this->assertNotNull($film);
        $this->assertEqualsIgnoringCase($titolo, $film->titolo);

    }
}
