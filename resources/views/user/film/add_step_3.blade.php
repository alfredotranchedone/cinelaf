@extends('layouts.app')

@section('content')
    <div class="container">

        @include('inc.back')

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">

                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span>Aggiungi Film</span>
                        <span class="small">Step 3 di 3</span>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-4">
                            <div class="flex-fill border-bottom border-success mr-2">
                                <h4 class="text-success">1.</h4>
                            </div>
                            <div class="flex-fill border-bottom border-success mr-2">
                                <h4 class="text-success">2.</h4>
                            </div>
                            <div class="flex-fill border-bottom border-success">
                                <h4 class="text-success">3.</h4>
                            </div>
                        </div>

                        <form action="{{ route('film.create') }}" enctype="multipart/form-data" method="post">
                            @csrf

                            <div class="form-group">
                                <label for="titolo">Titolo del Movie</label>
                                <input type="text"
                                       class="form-control-plaintext form-control-lg "
                                       name="titolo"
                                       id="titolo"
                                       readonly
                                       value="{{ $titolo }}">
                            </div>


                            <div class="form-group">
                                <label for="regista">Regista / i</label>
                                <input type="text"
                                       class="form-control-plaintext form-control-lg "
                                       name="regista"
                                       id="regista"
                                       readonly
                                       value="{{ $regista_string }}">
                            </div>


                            @php
                                if($type == \Cinelaf\Configuration\Configuration::TYPE_MOVIE){
                                    $label = __('Movies');
                                } elseif ($type == \Cinelaf\Configuration\Configuration::TYPE_SERIES){
                                    $label = __('Series');
                                }
                            @endphp
                            <div class="form-group">
                                <label for="regista">Tipologia</label>
                                <input type="text"
                                       class="form-control-plaintext form-control-lg "
                                       name="type"
                                       id="type"
                                       readonly
                                       value="{{ $label }}">
                            </div>


                            <div class="form-group">
                                <label for="anno">Anno</label>
                                <select class="form-control select2 {{ $errors->has('anno') ? 'is-invalid' : '' }}"
                                        name="anno"
                                        required
                                        data-placeholder="Seleziona l'anno di uscita..."
                                        id="anno">
                                    @for($x=date('Y'); $x>=1888; $x--)
                                        <option value="{{ $x }}">{{ $x }}</option>
                                    @endfor
                                </select>
                                {!! $errors->first('anno','<div class="invalid-feedback">:message</div>') !!}
                            </div>


                            <div class="form-group">
                                <label for="locandina">Carica Locandina</label>
                                <div class="custom-file">
                                    <input name="locandina" type="file" class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">Scegli File</label>
                                </div>
                                {!! $errors->first('locandina','<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="pt-2 pb-3 text-center">
                                <hr>
                                <button type="submit" class="btn btn-primary btn-lg mt-3">
                                    Salva Movie
                                </button>
                            </div>

                        </form>


                    </div>
                </div>
            </div>

        </div>

    </div>



@endsection



@push('scripts')
    <script>

        $(document).ready(function () {

            $('.select2-film').select2({
                theme: 'bootstrap4',
                tags: true,
                ajax: {
                    url: BASE_URL + '/api/film',
                    dataType: 'json',
                    processResults: function (data) {
                        console.log(data);
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.data
                        };
                    }
                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                }
            });


            $('.select2-regista').select2({
                theme: 'bootstrap4',
                tags: true,
                multiple: true,
                tokenSeparators: [','],
                ajax: {
                    url: BASE_URL + '/api/registi',
                    dataType: 'json',
                    processResults: function (data) {
                        console.log(data);
                        return {
                            results: data.data
                        };
                    }
                }
            });

        });

    </script>
@endpush