<?php

namespace Tests\Feature\Cinelaf\Repositories;

use App\User;
use Cinelaf\Models\DataMapper\UserNotRated;
use Cinelaf\Models\DataMapper\UserRated;
use Cinelaf\Models\DataMapper\UserRating;
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



    public function testFilterUserFilmRated()
    {

        $this->actingAs($this->user);

        $filmsfactory = factory(\Cinelaf\Models\Film::class,5)->create(['user_id'=>$this->user->id]);

        $film = factory(\Cinelaf\Models\Film::class)->create([
            'titolo'=>'## A Test ##',
            'user_id'=>$this->user->id
        ]);

        $film_another = factory(\Cinelaf\Models\Film::class)->create([
            'titolo'=>'## Z Test ##',
            'user_id'=>$this->user->id
        ]);

        $film->rating()->save(new Rating([
            'user_id' => $this->user->id,
            'voto' => 4.0
        ]));

        $film_another->rating()->save(new Rating([
            'user_id' => $this->user->id,
            'voto' => 3.5
        ]));

        $limit = 2;

        $repo = new Film();
        $data = $repo->filterMyRating('Test ##',0,$limit, 'titolo DESC');

        /* Result not null */
        $this->assertNotNull($data);

        /* Number of results */
        $this->assertCount($limit, $data);

        /* Test orderby */
        $this->assertEquals($data->first()->film->titolo,'## Z Test ##');

        /* Test data structure */
        $this->assertInstanceOf(UserRated::class, $data->first() );


    }




    public function testFilterUserFilmNotRated()
    {

        $this->actingAs($this->user);

        $filmsfactory = factory(\Cinelaf\Models\Film::class,5)->create(['user_id'=>$this->user->id]);
        factory(\Cinelaf\Models\Film::class)->create([
            'titolo'=>'## A Test ##',
            'user_id'=>$this->user->id
        ]);
        factory(\Cinelaf\Models\Film::class)->create([
            'titolo'=>'## Z Test ##',
            'user_id'=>$this->user->id
        ]);

        $limit = 2;

        $repo = new Film();
        $data = $repo->filterMyNotRated('Test ##',0,$limit, 'titolo DESC');

        /* Result not null */
        $this->assertNotNull($data);

        /* Number of results */
        $this->assertCount($limit, $data);

        /* Test orderby */
        $this->assertEquals($data->first()->titolo,'## Z Test ##');

        /* Test data structure */
        $this->assertInstanceOf(UserNotRated::class, $data->first() );


    }



    public function testSaveNewFilm()
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



    public function testGetAllLatestFilms()
    {

        $this->actingAs($this->user);

        factory(\Cinelaf\Models\Film::class, 10)->create([
            'user_id'=>$this->user->id
        ]);

        $filmRepo = new Film();

        /* With default limit */
        $data = $filmRepo->getLatestCreated();
        $this->assertNotNull($data);
        $this->assertInstanceOf(\Cinelaf\Models\Film::class, $data->first());

        /* With forced limit */
        $data = $filmRepo->getLatestCreated(7);
        $this->assertCount(7, $data);

    }


    public function testGetOnlySoftDeletedFilms()
    {

        $film_deleted_total = 2;

        $this->actingAs($this->user);
        factory(\Cinelaf\Models\Film::class, $film_deleted_total)->create([
            'user_id'=>$this->user->id,
            'deleted_at' => now()->toDateTimeString()
        ]);
        factory(\Cinelaf\Models\Film::class, 3)->create([
            'user_id'=>$this->user->id
        ]);

        $filmRepo = new Film();
        $data = $filmRepo->getTrashed();

        $this->assertNotNull($data);
        $this->assertCount($film_deleted_total, $data);

    }


    /**
     * Hard Delete a previously soft deleted record
     */
    public function testForceDeleteFilm()
    {

        $this->actingAs($this->user);
        $film = factory(\Cinelaf\Models\Film::class)->create([
            'titolo' => '## Delete ##',
            'user_id'=>$this->user->id,
            'deleted_at' => now()->toDateTimeString()
        ]);

        $filmRepo = new Film();
        $filmRepo->forceDelete($film->id);

        $this->assertDatabaseMissing('films',['titolo' => '## Delete ##', 'user_id' => $this->user->id]);

    }



    public function testUserAllRatedFilms()
    {

        $filmRepo = new Film();
        $this->actingAs($this->user);
        $films = factory(\Cinelaf\Models\Film::class,5)->create([
            'user_id'=>$this->user->id
        ]);

        $films->each(function ($film) {
            $film->rating()->save(new Rating([
                'user_id' => $this->user->id,
                'voto' => $this->faker->numberBetween(1,5)
            ]));
        });

        /* Check data inserted */
        $this->assertNotNull($films);
        $this->assertNotNull($films->first()->rating);

        /* Get ratings */
        $data = $filmRepo->myRating();
        $this->assertNotNull($data);
        $this->assertCount(5,$data);
        $this->assertInstanceOf(Rating::class, $data->first());

    }


    public function testUserAllRatedCount()
    {

        $filmRepo = new Film();
        $this->actingAs($this->user);
        $films = factory(\Cinelaf\Models\Film::class,5)->create([
            'user_id'=>$this->user->id
        ]);

        $films->each(function ($film) {
            $film->rating()->save(new Rating([
                'user_id' => $this->user->id,
                'voto' => $this->faker->numberBetween(1,5)
            ]));
        });

        /* Check data inserted */
        $this->assertNotNull($films);
        $this->assertNotNull($films->first()->rating);

        /* Get ratings */
        $data = $filmRepo->myRatingCount();
        $this->assertNotNull($data);
        $this->assertEquals(5,$data);

    }


}
