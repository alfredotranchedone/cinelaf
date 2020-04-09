<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

/* Override register */
Route::get('/register', function () {
    return redirect('/login');
});

Route::get('/home', 'HomeController@index')->name('home');

/* Internal API Rest */
Route::namespace('\Cinelaf\Controllers\Api')
    ->middleware('auth')
    ->group(function (){

        Route::prefix('api')
            ->name('api.')
            ->group(function (){


                Route::prefix('watchlist')
                    ->name('watchlist.')
                    ->group(function (){

                        Route::get('/get','WatchlistController@get_my_list')->name('get');
                        Route::post('/add','WatchlistController@post_add')->name('add');
                        Route::post('/remove','WatchlistController@post_remove')->name('remove');

                    });


                Route::prefix('film')
                    ->name('film.')
                    ->group(function (){

                        Route::get('/','FilmController@get_all')->name('all');
                        Route::any('/dt','FilmController@get_dt_all')->name('dt.all');
                        Route::any('/dt/myratings','FilmController@get_dt_myratings')->name('dt.myratings');
                        Route::any('/dt/mynotrated','FilmController@get_dt_mynotrated')->name('dt.mynotrated');
                        Route::any('/dt/noquorum','FilmController@get_dt_noquorum')->name('dt.noquorum');

                    });


                Route::prefix('registi')
                    ->name('registi.')
                    ->group(function (){

                        Route::get('/','RegistiController@get_all')->name('all');
                        Route::post('/create','RegistiController@post_create')->name('create');

                    });


                Route::prefix('rating')
                    ->name('rating.')
                    ->group(function (){

                        Route::post('/vote','RatingController@post_vote')->name('vote');

                    });

            });



    });


/* User */
Route::namespace('\Cinelaf\Controllers\User')
    ->middleware('auth')
    ->group(function (){

        /* Img */
        Route::get('img/locandina/{locandina?}','ImageController@get_locandina')->name('img.locandina');

        /* Profile */
        Route::get('me','ProfileController@get_me')->name('me');
        Route::put('me/{user}','ProfileController@put_me')->name('me.save');


        /* Watchlist */
        Route::get('watchlist','WatchlistController@get_index')->name('watchlist.index');
        Route::get('watchlist/{film}/add','WatchlistController@get_add')->name('watchlist.add');
        Route::post('watchlist/remove','WatchlistController@post_remove')->name('watchlist.remove');

        /* Movie */
        Route::prefix('u')
            ->name('user.')
            ->group(function () {

                Route::get('/{user}/ratings', 'UserController@get_ratings')->name('ratings');

            });

        /* Movie */
        Route::prefix('film')
            ->name('film.')
            ->group(function (){

                Route::get('add','FilmController@get_add')->name('add');
                Route::post('add/step-2','FilmController@post_add_step_2')->name('add.step_2');
                Route::post('add/step-3','FilmController@post_add_step_3')->name('add.step_3');

                Route::post('create','FilmController@post_create')->name('create');

                Route::get('my-ratings','FilmController@get_my_ratings')->name('myratings');
                Route::get('my-not-rated','FilmController@get_my_not_rated')->name('mynotrated');
                Route::get('no-quorum','FilmController@get_no_quorum')->name('noquorum');

                Route::get('/','FilmController@get_index')->name('index');
                Route::get('/{film}','FilmController@get_show')->name('show');

                /* Rating */
                Route::get('/{film}/vota','RatingController@get_vota')->name('vota');
                Route::post('/{film}/vota','RatingController@post_vota')->name('vota.save');
                Route::delete('/{film}/vota/delete','RatingController@delete')->name('vota.delete');

            });



        /* Movie */
        Route::prefix('series')
            ->name('series.')
            ->group(function (){

                Route::get('/','SeriesController@get_index')->name('index');

            });

        /* Registi */
        Route::prefix('registi')
            ->name('registi.')
            ->group(function (){

                // Route::get('add','FilmController@get_add')->name('add');

                Route::post('create','RegistiController@post_create')->name('create');

            });



    });


/* Admin */
Route::namespace('\Cinelaf\Controllers\Admin')
    ->prefix('/admin')
    ->name('admin.')
    ->middleware(['auth',\App\Http\Middleware\CheckSuperAdmin::class])
    ->group(function (){

        Route::get('dashboard','DashboardController@get_index')->name('dashboard');

        Route::get('rating/update-batch','RatingController@get_update_batch')->name('rating.updateBatch');

        Route::prefix('users')
            ->name('users.')
            ->group(function (){

                Route::get('/','UserController@get_index')->name('index');
                Route::get('/add','UserController@get_add')->name('add');
                Route::post('/add','UserController@post_create')->name('add.save');
                Route::get('/{user}/edit','UserController@get_edit')->name('edit');
                Route::put('/{user}/edit','UserController@put_update')->name('edit');
                Route::delete('/{user}/delete','UserController@delete')->name('delete');

            });


        Route::prefix('film')
            ->name('film.')
            ->group(function (){

                Route::get('/{film}/edit','FilmController@get_edit')->name('edit');
                Route::put('/{film}/edit','FilmController@put_update')->name('edit');
                Route::delete('/{film}/delete','FilmController@delete')->name('delete');
                Route::delete('/{film}/force-delete','FilmController@forceDelete')->name('forcedelete');

            });

    });




/* System */
Route::middleware(['web','auth'])
    ->prefix('system/')
    ->name('system.')
    ->group(
        function () {

            Route::get('/reset', function (){
                \Illuminate\Support\Facades\Artisan::call('route:clear');
                dump( \Illuminate\Support\Facades\Artisan::output() );
                \Illuminate\Support\Facades\Artisan::call('cache:clear');
                dump( \Illuminate\Support\Facades\Artisan::output() );
                \Illuminate\Support\Facades\Artisan::call('config:clear');
                dump( \Illuminate\Support\Facades\Artisan::output() );
                \Illuminate\Support\Facades\Artisan::call('config:cache');
                dump( \Illuminate\Support\Facades\Artisan::output() );

                dump( 'reset done' );
            })->name('reset');

        }
    );



/* System */
Route::middleware(['web','auth'])
    ->prefix('command/')
    ->name('command.')
    ->group(
        function () {

            Route::get('/{cmd}', function ($cmd){
                \Illuminate\Support\Facades\Artisan::call($cmd);
                dump( \Illuminate\Support\Facades\Artisan::output() );
            })->name('index');

        }
    );


/*
Route::middleware(['web',])
    ->prefix('g/')
    ->name('g.')
    ->group(
        function () {

            Route::get('/', function (){

                $systemUser = new \App\User();
                $systemUser->name = 'system';
                $systemUser->email = 'system@cinelaf.it';
                $systemUser->password = bcrypt('password-da-modificare');
                $systemUser->is_super_admin = 1;
                $systemUser->save();

            })->name('index');

        }
    );
*/
