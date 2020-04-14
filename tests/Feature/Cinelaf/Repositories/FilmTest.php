<?php

namespace Tests\Feature\Cinelaf\Repositories;

use App\User;
use Cinelaf\Models\Rating;
use Cinelaf\Models\Regista;
use Cinelaf\Repositories\Film;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class FilmTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    protected $user;
    protected $repo;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

    }


    public function testGetFullFilmCollection()
    {

        $filmsfactory = factory(\Cinelaf\Models\Film::class,4)->create(['user_id'=>$this->user->id]);
        $film = factory(\Cinelaf\Models\Film::class)->create([
            'titolo'=>'##Prova##',
            'user_id'=>$this->user->id
        ]);

        $repo = new Film();
        $data = $repo->get('full');

        $this->assertNotNull($data);
        $this->assertCount(5,$data);

    }



    public function testGetFilteredFilmCollectionWithOwnerAndRegista()
    {

        $filmsfactory = factory(\Cinelaf\Models\Film::class,5)->create(['user_id'=>$this->user->id]);
        $film = factory(\Cinelaf\Models\Film::class)->create([
            'titolo'=>'##Prova##',
            'user_id'=>$this->user->id
        ]);
        $regista = factory(Regista::class)->create();

        $film->regista()->attach($regista->id);

        $repo = new Film();
        $data = $repo->get('full',5,'##Prova##',true,true);

        $this->assertNotNull($data);
        $this->assertCount(1,$data);

        $this->assertInstanceOf(User::class,$data->first()->user()->first());
        $this->assertInstanceOf(Regista::class,$data->first()->regista()->first());

    }



    public function testFilterFilmCollection()
    {

        $filmsfactory = factory(\Cinelaf\Models\Film::class,5)->create(['user_id'=>$this->user->id]);
        $films = factory(\Cinelaf\Models\Film::class,5)->create([
            'titolo'=>'##Prova##',
            'user_id'=>$this->user->id
        ]);

        $repo = new Film();
        $data = $repo->filter('##Prova##');

        $this->assertNotNull($data);
        $this->assertCount(5,$data);

    }

    public function testFilterUserOwnRating()
    {

        $this->actingAs($this->user);

        $filmsfactory = factory(\Cinelaf\Models\Film::class,5)->create(['user_id'=>$this->user->id]);
        $film = factory(\Cinelaf\Models\Film::class)->create([
            'titolo'=>'Prova',
            'user_id'=>$this->user->id
        ]);
        $film->rating()->save(new Rating([
            'user_id' => $this->user->id,
            'voto' => 4.0
        ]));

        $repo = new Film();
        $data = $repo->filterMyRating();

        $this->assertNotNull($data);
        $this->assertInstanceOf(Collection::class, $data );

    }


    /**
     *
     * @return void
     * @test
     */
    public function save_film()
    {

        $this->actingAs($this->user);

        $filmRepo = new Film();

        $titolo = $this->faker->sentence();
        $type = $this->faker->numberBetween(1,2);
        $anno = $this->faker->year;
        $locandina = Str::slug($titolo) . '.jpg';

        $film = $filmRepo->save($titolo,$anno,$locandina,$type);

        $this->assertNotNull($film);
        $this->assertEqualsIgnoringCase($titolo, $film->titolo);

    }

}
