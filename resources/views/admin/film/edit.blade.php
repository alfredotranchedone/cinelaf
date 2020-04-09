@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span>Modifica Film</span>
                    </div>

                    <div class="p-3 border-bottom">
                        <a href="{{ route('film.show',$film) }}" class="btn btn-light">
                            <i class="fa fa-angle-left fa-fw"></i>
                            Annulla
                        </a>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('admin.film.edit', [$film]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="form-group">
                              <label for="titolo">Titolo</label>
                              <input type="text"
                                    class="form-control {{ $errors->has('titolo') ? 'is-invalid' : '' }}"
                                    name="titolo"
                                    id="titolo"
                                    value="{{ old('titolo', $film->titolo) }}"
                                    placeholder="Titolo">
                                    {!! $errors->first('titolo','<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="anno">Anno</label>
                                <select class="form-control select2 {{ $errors->has('anno') ? 'is-invalid' : '' }}"
                                        name="anno"
                                        required
                                        data-placeholder="Seleziona l'anno di uscita..."
                                        style="max-width: 100px"
                                        id="anno">
                                    @for($x=date('Y'); $x>=1888; $x--)
                                        <option {{ $x == old('anno',$film->anno) ? 'selected' : '' }} value="{{ $x }}">{{ $x }}</option>
                                    @endfor
                                </select>
                                {!! $errors->first('anno','<div class="invalid-feedback">:message</div>') !!}
                            </div>


                            <section class="mb-3 py-3">

                                <div class="form-group">
                                    <label for="regista">Regista / i </label>
                                    <select name="regista[]"
                                            id="regista"
                                            required
                                            multiple
                                            data-placeholder="Seleziona i registi..."
                                            class="form-control select2-regista">
                                        @if (is_array(old('regista',$film->regista->pluck('id')->toArray() )))
                                            @foreach (old('regista',$film->regista) as $reg)
                                                @php
                                                    /* Solo se old('regista') è valorizzato */
                                                    if(old('regista')){
                                                        $nominativo = (new \Cinelaf\Repositories\Registi)->getNominativoFromId([ $reg ], true);
                                                    } else {
                                                        $nominativo = $reg->nome .' '. $reg->cognome;
                                                    }
                                                @endphp
                                                <option value="{{ is_object($reg) ? $reg->id : $reg }}" selected="selected">{{ $nominativo }}</option>
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

                            </section>


                            <div class="media">
                                <img src="{{ route('img.locandina',[$film->locandina]) }}" class="mr-3 img-thumbnail" alt="" width="120">
                                <div class="media-body pt-3">

                                    <div class="form-group">
                                        <label for="locandina">Locandina</label>
                                        <div class="custom-file">
                                            <input name="locandina" type="file" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile">Carica Nuova Locandina...</label>
                                        </div>
                                        {!! $errors->first('locandina','<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                </div>
                            </div>




                            <div class="text-center mt-5 pt-3 pb-3 bg-light rounded">
                                <button type="submit" class="btn btn-primary">
                                    Salva Modifiche
                                </button>
                            </div>

                        </form>

                    </div>

                    <div class="card-footer">
                        <form action="{{ route('admin.film.delete', [$film]) }}"
                              onsubmit="return confirm('Proseguire con l\'eliminazione?');"
                              method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-user-plus fa-fw"></i>
                                Elimina Film
                            </button>
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