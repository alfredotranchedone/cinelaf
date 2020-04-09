@extends('layouts.app')

@section('content')
    <div class="container">

        @include('inc.back',[
            'url' => route('film.add')
        ])

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">

                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span>Aggiungi Film</span>
                        <span class="small">Step 2 di 3</span>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-4">
                            <div class="flex-fill border-bottom border-success mr-2">
                                <h4 class="text-success">1.</h4>
                            </div>
                            <div class="flex-fill border-bottom border-success mr-2">
                                <h4 class="text-success">2.</h4>
                            </div>
                            <div class="flex-fill border-bottom border-light">
                                <h4 class="text-light">3.</h4>
                            </div>
                        </div>

                        <form action="{{ route('film.add.step_3') }}" method="post">
                            @csrf

                            <div class="form-group">
                                <label for="titolo">Titolo del Film</label>
                                <input type="text"
                                       class="form-control-plaintext form-control-lg {{ $errors->has('titolo') ? 'is-invalid' : '' }}"
                                       name="titolo"
                                       id="titolo"
                                       readonly
                                       value="{{ session( config('cinelaf.sessions_key.film.new') )['titolo'] }}">
                            </div>


                            <div class="form-group">
                                <label for="regista">Regista / i </label>
                                <select name="regista[]"
                                        id="regista"
                                        required
                                        data-placeholder="Seleziona i registi..."
                                        class="form-control select2-regista">
                                    @if (is_array(old('regista')))
                                        @foreach (old('regista') as $reg)
                                            <option value="{{ $reg }}" selected="selected">{{ $reg }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="d-flex mt-1 justify-content-center align-items-center">
                                <span class="d-inline-block small mr-1">Non trovi il Regista?</span>
                                <button type="button"
                                        data-toggle="modal"
                                        data-target="#modal-regista-add"
                                        class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus-circle fa-fw"></i>
                                    Aggiungi un Nuovo Regista
                                </button>
                            </div>


                            <div class="pt-2 pb-3 text-center">
                                <hr>
                                <button id="btnGoToStep3" type="submit" class="btn btn-primary btn-lg mt-3" disabled>
                                    Prosegui
                                </button>
                            </div>


                        </form>


                    </div>
                </div>
            </div>

        </div>

    </div>



    <!-- Modal -->
    <div id="modal-regista-add" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aggiungi Regista</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="frmAddNewRegista">
                        @csrf
                        <div class="form-group">
                            <label for="nome">Nome Regista</label>
                            <input type="text"
                                   class="form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                                   name="nome"
                                   id="nome"
                                   required
                                   maxlength="150"
                                   minlength="2"
                                   value="{{ old('nome') }}"
                                   placeholder="Nome Regista">
                            {!! $errors->first('nome','<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                          <label for="cognome">Cognome Regista</label>
                          <input type="text"
                                class="form-control {{ $errors->has('cognome') ? 'is-invalid' : '' }}"
                                name="cognome"
                                id="cognome"
                                 maxlength="150"
                                 minlength="2"
                                 required
                                value="{{ old('cognome') }}"
                                placeholder="Cognome Regista">
                                {!! $errors->first('cognome','<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Chiudi</button>
                    <button type="button" class="btn btn-primary" onclick="saveRegista();">Salva Nuovo Regista</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-->


@endsection



@push('scripts')
    <script>



        function saveRegista(){


            if( ! $('#frmAddNewRegista').valid() ){
                return false;
            }

            console.log('regista:create:start');

            window.axios.post(BASE_URL + '/api/registi/create', {
                nome: $('#nome').val(),
                cognome: $('#cognome').val()
            })
            .then(function (response) {
                console.log('regista:create:save');
                alert('Regista salvato');
            })
            .catch(function (error) {
                console.log('regista:create:error');
                console.log(error);
                alert('Si è verificato un errore di comunicazione');
            })
            .then(function () {
                console.log('regista:create:done');
                $('#modal-regista-add').modal('hide');
            });

        }



        $(document).ready(function () {

            let $selectRegista = $('.select2-regista');

            $('#modal-regista-add').on('hide.bs.modal', function (e) {
                $('#frmAddNewRegista input').val('');
            });

            $selectRegista.select2({
                theme: 'bootstrap4',
                multiple: true,
                tokenSeparators: [','],
                ajax: {
                    url: BASE_URL + '/api/registi',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: params.term,
                            format: 'select'
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.data
                        };
                    }
                }
            });

            //

            $selectRegista.on('select2:close',function (e) {

                let _disabled = true;
                if( $selectRegista.select2('data').length > 0 ){
                    _disabled = false
                }
                $('#btnGoToStep3').attr('disabled',_disabled);

            });

        });

    </script>
@endpush