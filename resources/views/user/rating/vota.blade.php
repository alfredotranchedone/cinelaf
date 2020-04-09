@extends('layouts.app')

@section('content')
    <div class="container">

        @include('inc.back',[
            'url' => url()->previous() ?? route('home')
        ])

        <div class="row justify-content-center mt-4">

            <div class="col-md-8">


                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <span>Vota il Movie</span>
                    </div>

                    <div class="card-body">

                        <h3>{{ $film->titolo }}
                            <small>({{ $film->anno }})</small>
                        </h3>


                        <div class="bg-light rounded">

                            <form action="{{ route('film.vota.save', [$film]) }}" method="post">

                                @csrf

                                <div class="d-flex flex-column align-items-center mt-4 px-3 py-4">

                                    @if( $myRating )
                                        <div class="text-center alert alert-info">
                                            <i class="fa fa-info-circle fa-fw"></i>
                                            Hai votato per questo film {{ $myRating->updated_at->diffForHumans() }}
                                            <br>
                                            <small>({{ $myRating->created_at->formatLocalized('%A %d %B %Y') }})</small>
                                        </div>
                                    @endif

                                    <div>
                                        <label for="voto" class="font-weight-bold d-block text-center">Valuta il film:</label>
                                        <select name="voto"
                                                class="form-control form-control-lg my-3"
                                                style="width: 250px"
                                                required
                                                id="voto">
                                            <option value="" selected disabled>Scegli un voto...</option>
                                            @for($x=0.5;$x<=5;$x+=0.5)
                                                <option {{ optional($myRating)->voto == $x ? 'selected' : '' }} value="{{ $x }}">{{ $x }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            Vota Ora!
                                            <i class="fa fa-angle-right fa-fw"></i>
                                        </button>
                                    </div>

                                </div>

                            </form>
                        </div>

                    </div>

                    @if( $myRating )
                    <div class="card-footer">
                        <form action="{{ route('film.vota.delete',[$film]) }}"
                              method="post"
                              onsubmit="return confirm('Rimuovere il tuo voto?');">
                            @csrf
                            @method('delete')

                            <input name="ratingId"
                                   id="ratingId"
                                   type="hidden"
                                   value="{{ encrypt($myRating->id) }}" >

                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash fa-fw"></i>
                                Elimina il voto
                            </button>

                        </form>
                    </div>
                    @endif

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