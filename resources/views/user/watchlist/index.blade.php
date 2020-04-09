@extends('layouts.app')

@section('content')
    <div class="container">

        @include('inc.back',[
            'url' => url()->previous() ?? route('home')
        ])

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <span>La Mia Watchlist</span>
                    </div>

                    <div class="card-body">


                        <ul class="list-unstyled">

                        @forelse($list as $l)
                            <li class="media mb-3 {{ $loop->index == 0 ? '' : 'pt-3' }} {{ $loop->index > 0 ? 'border-top' : '' }}">
                                <a href="{{ route('film.show',$l->film_id) }}" class="text-decoration-none">
                                    <img src="/img/locandina/{{ $l->film->locandina }}" alt="" style="width: 75px" class="mr-3">
                                </a>
                                <div class="media-body">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between">
                                        <div>
                                            <h4 class="mt-0">
                                                <a href="{{ route('film.show',$l->film_id) }}" class="text-decoration-none">
                                                    {{ $l->film->titolo }} <small> ({{ $l->film->anno ?? '-' }}) </small>
                                                </a>
                                            </h4>
                                            <div class="mb-2 small">
                                                @include('inc.registi',[
                                                    'registi' => $l->film->regista
                                                ])
                                            </div>
                                            @php
                                                $cls = 'light';
                                                if($l->film->valutazione > 0)
                                                    $cls = 'success';
                                            @endphp
                                            <div class="badge badge-{{ $cls }} p-1">
                                                <i class="fa fa-star fa-fw"></i>
                                                {{ $l->film->valutazione }}
                                            </div>
                                        </div>

                                        <div class="mt-3 mt-sm-0">
                                            <form action="{{ route('watchlist.remove') }}" method="post"
                                                  onsubmit="return confirm('Rimuovere il film dalla Watchlist?');">
                                                @csrf
                                                <input name="filmId" id="filmId" type="hidden" value="{{ $l->film_id }}">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fa fa-trash fa-fw"></i>
                                                    <span class="d-inline d-sm-none">Rimuovi</span>
                                                </button>
                                            </form>
                                        </div>

                                    </div>


                                </div>
                            </li>


                        @empty

                            <div class="alert alert-info">
                                <i class="fa fa-info-circle fa-fw"></i>
                                Non hai Movie nella watchlist.
                            </div>

                        @endforelse
                        </ul>


                    </div>
                </div>
            </div>

        </div>

    </div>



@endsection



@push('scripts')
    <script>

        $(document).ready(function () {

            let dtOptions = $.extend(true, {}, window.dataTableDefaultOptions, {
                "pageLength": 10,
                "order": [[3, 'desc']]
            });

            $('.dataTableFilm').dataTable(dtOptions);

        });

    </script>
@endpush