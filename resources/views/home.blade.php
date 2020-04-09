@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">

                    <div class="d-flex px-3 pt-2 pb-2 justify-content-center align-items-center text-secondary">
                        <div>
                            <i class="fa fa-user-circle mt-1" style="font-size: 1.5em"></i>
                        </div>
                        <div class="font-weight-light text-capitalize mx-3 text-truncate" style="font-size: 1.6em">
                            {{ auth()->user()->name }}
                        </div>
                    </div>

                    <div>
                        <div class="progress" style="height: 2px; border-radius: 0">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalFilm > 0 ? round(($myRatingCount * 100) / $totalFilm, 0) : '0' }}%"></div>
                        </div>
                    </div>
                    <div class="card-body p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center">

                        <div class="d-flex flex-even justify-content-center align-items-baseline">
                            <h1 class="mb-0 mr-2 font-weight-bold">{{ $totalFilm }}</h1>
                            <div class="text-secondary">film presenti</div>
                        </div>

                        <div class="d-flex flex-even justify-content-center align-items-baseline no-border-sm border-left border-right">
                            <h1 class="mb-0 mr-2 font-weight-bold">{{ $myRatingCount }}</h1>
                            <div class="text-secondary">film valutati</div>
                        </div>

                        <div class="d-flex flex-even justify-content-center align-items-baseline">
                            <h1 class="mb-0 mr-2 font-weight-bold">{{ ($totalFilm - $myRatingCount) }}</h1>
                            <div class="text-secondary">film da valutare</div>
                        </div>

                    </div>
                </div>
            </div>

        </div>



        <div class="row mt-5 justify-content-center">

            <div class="col-sm-4">
                <div class="card shadow mt-4 mt-sm-0">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h4 class="card-title text-indigo">
                            <i class="far fa-check-circle fa-fw"></i>
                            I Miei Voti
                        </h4>
                        <p class="card-text py-2 text-center">
                            I film che hai valutato
                        </p>
                        <a href="{{ route('film.myratings') }}" class="btn btn-primary btn-block mt-auto">
                            Mostra i Movie Valutati
                            <i class="fa fa-angle-right fa-fw"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card shadow mt-4 mt-sm-0">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h4 class="card-title text-indigo">
                            <i class="far fa-star fa-fw"></i>
                            Da Valutare
                        </h4>
                        <p class="card-text py-2 text-center">I film in attesa della tua valutazione</p>
                        <a href="{{ route('film.mynotrated') }}" class="btn btn-primary btn-block mt-auto">
                            Valuta Ora
                            <i class="fa fa-angle-right fa-fw"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->



        <div class="row justify-content-center mt-4">

            <div class="col-sm-4">
                <div class="card shadow">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h4 class="card-title text-indigo">
                            <i class="far fa-edit fa-fw"></i>
                            Inserisci Movie
                        </h4>
                        <p class="card-text py-2 text-center">Aggiungi un nuovo film</p>
                        <a href="{{ route('film.add') }}" class="btn btn-primary btn-block mt-auto">
                            Aggiungi Movie
                            <i class="fa fa-angle-right fa-fw"></i> </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card shadow mt-4 mt-sm-0">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h4 class="card-title text-indigo">
                            <i class="far fa-list-alt fa-fw"></i>
                            Tutti i Movie
                        </h4>
                        <p class="card-text py-2 text-center">
                            Tutti i film presenti nel sistema
                        </p>
                        <a href="{{ route('film.index') }}" class="btn btn-primary btn-block mt-auto">
                            Mostra Tutti i Movie
                            <i class="fa fa-angle-right fa-fw"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->

        <!-- Latest -->
        <div class="row mt-0 mt-sm-4 justify-content-center">

            <div class="col-sm-8">
                <div class="card shadow mt-4 mt-sm-0">
                    <div class="card-body p-0">

                        <h4 class="card-title px-3 pt-3 text-indigo">
                            <i class="far fa-file-video fa-fw"></i>
                            Ultimi 5 Inserimenti
                        </h4>

                        <div class="list-group list-group-flush latest-film border-top">

                            @forelse($latestFilm as $film)
                                <a href="{{ route('film.show',[$film]) }}" class="list-group-item list-group-item-action border-bottom p-2 d-flex justify-content-start">

                                    <div class="mr-2">
                                        <img src="{{ route('img.locandina', [ $film->locandina ]) }}" alt="" style="max-width: 75px">
                                    </div>
                                    <div>
                                        <div class="">
                                            <i class="far fa-bookmark fa-fw"></i>
                                            {{ $film->titolo }} <small>({{ $film->anno }})</small>
                                        </div>
                                        <div class="">
                                            <i class="far fa-calendar-alt fa-fw"></i>
                                            {{ optional($film->created_at)->format('d-m-Y') }}
                                        </div>
                                        <div class="">
                                            <i class="far fa-user fa-fw"></i>
                                            {{ $film->user->name }}
                                        </div>
                                    </div>

                                </a>
                            @empty
                                <div class="p-2">
                                    Nessun film presente in archivio.
                                </div>
                            @endforelse

                        </div>

                    </div>
                </div>
            </div>

        </div>


        <!-- Best -->
        <div class="row mt-0 mt-sm-4 justify-content-center">

            <div class="col-sm-8">
                <div class="card shadow mt-4 mt-sm-0">
                    <div class="card-body p-0">

                        <h4 class="card-title text-indigo px-3 pt-3 ">
                            <i class="far fa-file-video fa-fw"></i>
                            Top {{ $bestRated->count() }}
                        </h4>


                        @include('inc.list_rated',[
                            'ratings' => $bestRated
                        ])

                    </div>
                    <div class="card-footer">
                        <a href="{{ route('film.index') }}" class="btn btn-primary btn-block mt-auto">
                            Mostra Tutti i Movie
                            <i class="fa fa-angle-right fa-fw"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>


        {{--
        <!-- Worst -->
        <div class="row mt-0 mt-sm-4 justify-content-center">

            <div class="col-sm-8">
                <div class="card shadow mt-4 mt-sm-0">
                    <div class="card-body p-0">

                        <h4 class="card-title text-indigo px-3 pt-3">
                            <i class="far fa-file-video fa-fw"></i>
                            Flop {{ $worstRated->count() }}
                        </h4>

                        @include('inc.list_rated',[
                            'ratings' => $worstRated
                        ])

                    </div>
                </div>
            </div>

        </div>
        --}}



    </div>
@endsection
