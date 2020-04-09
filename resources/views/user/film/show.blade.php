@extends('layouts.app')

@section('content')
    <div class="container">

        @include('inc.back',[
            'url' => route('film.index')
        ])


        <div class="row justify-content-center mt-4">

            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white d-flex justify-content-between">
                        <span>Scheda Film</span>
                        <div>
                            <span class="badge badge-light">
                                # {{ $film->rank ?? '-' }}
                            </span>
                        </div>
                    </div>

                    @if(auth()->user()->isSuperAdmin())
                        <div class="p-3 border-bottom">

                            <a href="{{ route('admin.film.edit', [$film]) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fa fa-edit fa-fw"></i>
                                Modifica Scheda
                            </a>

                        </div>
                    @endif

                    <div class="card-body">

                        <div class="d-flex flex-column flex-lg-row">

                            <div class="order-1 order-lg-0 mt-4 mt-lg-0 text-center ">
                                <img src="{{ route('img.locandina', $film->locandina) }}"
                                     class="img-thumbnail"
                                     alt=""
                                     style="max-width: 250px">
                            </div>

                            <div class="order-0 order-lg-1 mx-3 d-flex flex-fill flex-column justify-content-between">
                                <div>

                                    <div class="mb-2">

                                        <span class="badge badge-success">
                                            @if($film->isSeries())
                                                <i class="fa fa-tv fa-fw"></i>
                                                {{ __('Series') }}
                                            @elseif($film->isMovie())
                                                <i class="fa fa-film fa-fw"></i>
                                                {{ __('Movie') }}
                                            @endif
                                        </span>

                                    </div>

                                    <h5 class="mb-0">{{ $film->anno }}</h5>

                                    <h2 class="">{{ $film->titolo }}</h2>

                                    <h6 class="mb-2">
                                        @include('inc.registi',[
                                            'registi' => $film->regista
                                        ])
                                    </h6>

                                    <section>

                                        @php
                                            $sanitizedTitle = urlencode($film->titolo);
                                        @endphp

                                        <div class="pt-1 pb-0">
                                            <a href="https://www.mymovies.it/database/ricerca/avanzata/?titolo={{ $sanitizedTitle }}&titolo_orig=&regista=&attore=&id_genere=-1&nazione=&clausola1=-1&anno_prod={{ $film->anno }}&clausola2=-1&stelle=-1&id_manif=-1&anno_manif=&disponib=-1&ordinamento=0&submit=Inizia+ricerca+%C2%BB"
                                               target="_blank"
                                               class="small text-decoration-none">
                                                <i class="fa fa-search fa-fw"></i>
                                                Cerca su MyMovies.it
                                                <i class="fa fa-angle-double-right fa-fw"></i>
                                            </a>
                                        </div>

                                        <div class="pt-1 pb-2">
                                            <a href="https://www.imdb.com/find?q={{ $sanitizedTitle }}"
                                               target="_blank"
                                               class="small text-decoration-none">
                                                <i class="fa fa-search fa-fw"></i>
                                                Cerca su IMDb.com
                                                <i class="fa fa-angle-double-right fa-fw"></i>
                                            </a>
                                        </div>
                                    </section>

                                </div>

                                <div>

                                    <div class="mb-2">
                                        <div class="text-secondary small">Inserito:</div>
                                        <div>
                                            {{ $film->created_at->diffForHumans() }}
                                            <small class="text-secondary">
                                                ({{ $film->created_at->formatLocalized('%A %d %B %Y') }})
                                            </small>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="text-secondary small">Caricato da:</div>
                                        <div>{{ $film->user->name }}</div>
                                    </div>

                                </div>

                                @if( count($film->rating->where('user_id', auth()->id())) > 0 )

                                    <div class="d-flex flex-column flex-sm-row  align-items-start justify-content-between">
                                        <div>
                                            <button type="button" disabled class="btn btn-primary">
                                                <i class="fa fa-ban fa-fw"></i> Hai già votato
                                            </button>
                                            <div class="pt-2 text-secondary ml-3">
                                                Il tuo voto è
                                                {{ optional($film->rating->firstWhere('user_id', auth()->id()))->voto }}
                                            </div>
                                        </div>

                                        <div class="small pt-2 ml-3 ml-sm-0 text-center">
                                            <a href="{{ route('film.vota',[$film]) }}" class="text-decoration-none">
                                                <i class="far fa-edit fa-fw"></i>
                                                Modifica il voto
                                            </a>
                                        </div>

                                    </div>

                                @else

                                    <div>
                                        @include('inc.vota', ['film_id' => $film])
                                    </div>

                                @endif

                                <div class="d-flex flex-column flex-sm-row align-items-start justify-content-between mt-4">
                                    @include('inc.add_to_watchlist', ['film' => $film])
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>


        <div class="row justify-content-center mt-4">
            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <span>Rank</span>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h1 class="display-3 mx-3 mx-sm-0 {{ $film->rank ? 'font-weight-bold' : 'text-secondary' }}">
                                # {{ $film->rank ?? '-' }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <span>Rating</span>
                    </div>

                    <div class="card-body p-0">

                        @if($valutazione == 0)
                            <div class=" m-2 alert alert-info">
                                <i class="fa fa-info-circle fa-fw"></i>
                                <b>NB</b>
                                @php
                                    $quorum = config('cinelaf.quorum') ;
                                    $missing = ($quorum - $rating->count());
                                @endphp
                                @if($missing > 1)
                                    Occorrono altri {{ $missing }} voti per calcolare la valutazione
                                @else
                                    Occorre ancora 1 voto per calcolare la valutazione
                                @endif
                            </div>
                        @endif

                        <div class="row mx-2 mt-lg-4 mt-3">

                            <div class="col-12">
                                <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center align-items-lg-baseline mb-3">
                                    <h1 class="display-3 mb-0 mr-2 font-weight-bold">
                                        @if($valutazione > 0)
                                            {{ $valutazione }}
                                        @else
                                            <span class="text-light">
                                                n.d.
                                            </span>
                                        @endif
                                    </h1>
                                    <div class="text-secondary">valutazione</div>
                                </div>
                            </div>

                            <div class="col-6 col-sm-3">
                                <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center align-items-lg-baseline">
                                    <h1 class="mb-0 mr-0 mt-2 mt-lg-0 mr-md-2 font-weight-bold">
                                        {{ $rating->count() }}
                                    </h1>
                                    <div class="text-secondary">voti</div>
                                </div>
                            </div>

                            <div class="col-6 col-sm-3">
                                <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center align-items-lg-baseline">
                                    <h1 class="mb-0 mr-2 font-weight-bold">
                                        {{ number_format($rating->avg('voto'),1,'.',',') }}
                                    </h1>
                                    <div class="text-secondary">media voto</div>
                                </div>
                            </div>

                            <div class="col-6 col-sm-3">
                                <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center align-items-lg-baseline">
                                    <h1 class="mb-0 mr-2 font-weight-bold">
                                        {{ ($rating->max('voto')) ?? 0 }}
                                    </h1>
                                    <div class="text-secondary">max voto</div>
                                </div>
                            </div>

                            <div class="col-6 col-sm-3">
                                <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center align-items-lg-baseline">
                                    <h1 class="mb-0 mr-2 mt-2 mt-lg-0 font-weight-bold">
                                        {{ ($rating->min('voto')) ?? 0 }}
                                    </h1>
                                    <div class="text-secondary">min voto</div>
                                </div>
                            </div>

                        </div>
                        <!-- /.row -->


                        <div class="list-group list-group-flush mt-4 mt-lg-4">

                            <div class="list-group-item d-flex bg-light">
                                <div class="flex-even font-weight-bold">Utente</div>
                                <div class="flex-even font-weight-bold text-center">Voto</div>
                                <div class="flex-even font-weight-bold text-right">Data Voto</div>
                            </div>

                            @forelse($film->rating as $rating)
                                <div class="list-group-item d-flex {{ $rating->user_id == auth()->id() ? 'text-success' : '' }}">
                                    <div class="flex-even">
                                        <a href="{{ route('user.ratings',$rating->user_id) }}">
                                            {{ $rating->user->name }}
                                        </a>
                                    </div>
                                    <div class="flex-even text-center">{{ $rating->voto }}</div>
                                    <div class="flex-even text-right">{{ $rating->updated_at->diffForHumans() }}</div>
                                </div>
                            @empty
                            @endforelse

                        </div>

                        <div class="text-center my-4">

                            @if( count($film->rating->where('user_id', auth()->id())) > 0 )
                                <div>
                                    <button type="button" disabled class="btn btn-primary">
                                        <i class="fa fa-ban fa-fw"></i> Hai già votato
                                    </button>
                                    <div class="small pt-2 text-center">
                                        <a href="{{ route('film.vota', $film->id) }}" class="text-decoration-none">
                                            <i class="far fa-edit fa-fw"></i>
                                            Modifica il voto
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div>
                                    @include('inc.vota', ['film_id' => $film])
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>

        </div>


    </div>
@endsection



@push('scripts')
    <script>

        $(document).ready(function () {


        });

    </script>
@endpush