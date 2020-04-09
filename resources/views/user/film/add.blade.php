@extends('layouts.app')

@section('content')
    <div class="container">

        @include('inc.back',[
            'url' => route('home')
        ])

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span>Aggiungi Film</span>
                        <span class="small">Step 1 di 3</span>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-4">
                            <div class="flex-fill border-bottom border-success mr-2">
                                <h4 class="text-success">1.</h4>
                            </div>
                            <div class="flex-fill border-bottom border-light mr-2">
                                <h4 class="text-light">2.</h4>
                            </div>
                            <div class="flex-fill border-bottom border-light">
                                <h4 class="text-light">3.</h4>
                            </div>
                        </div>

                        <form id="frmGoToStep2" action="{{ route('film.add.step_2') }}" method="post">
                            @csrf

                            <div class="alert alert-info small">
                                <i class="fa fa-info-circle fa-fw mr-2"></i>
                                Prima di inserire un nuovo Film, controlla che non sia già presente in archivio
                            </div>

                            <label for="titolo">
                                <span>Titolo del Film <span id="resultCount"></span></span>
                                <span id="loading" class="spinner-border spinner-border-sm ml-1" style="display: none;"></span>
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="text"
                                       class="form-control"
                                       name="titolo"
                                       id="titolo"
                                       required
                                       value="{{ old('titolo') }}"
                                       placeholder="Cerca il titolo...">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">
                                        <i class="fa fa-search fa-fw"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="list-group" id="results"></div>

                            <div class="pt-3 pb-3 text-center">
                                <hr>
                                <button id="btnSend" type="submit" class="btn btn-primary btn-lg mt-3">
                                    Prosegui
                                    <i class="fa fa-angle-right fa-fw"></i>
                                </button>
                            </div>

                        </form>


                    </div>
                </div>
            </div>

        </div>

    </div>


    <!-- Modal -->
    <div id="modal-confirm" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Film esistente</h4>
                </div>
                <div class="modal-body">
                    Vuoi proseguire con l'inserimento del film?
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" onclick="$('#frmGoToStep2').submit();">Sì, prosegui</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-->

@endsection



@push('scripts')
    <script>

        function search(evt){

            const $btnSend = $('#btnSend');
            const $results = $('#results');
            const $resultCount = $('#resultCount');
            const $loading = $('#loading');

            $loading.show();

            if(evt.currentTarget.value.length < 1) {
                $results.empty();
                $resultCount.empty();
                $loading.hide();
                return;
            }

            window.axios
                .get(BASE_URL + '/api/film', {
                    params: {
                        q: evt.currentTarget.value
                    }
                })
                .then(function (response) {

                    let resultCount = response.data.length;

                    let html = window.Film.renderLiveCheck(response.data);
                    $results.html(html);

                    let _exists = (resultCount > 0);
                    // $btnSend.attr('disabled',_exists);

                    if(_exists){
                        $btnSend
                            .attr('disabled',false)
                            .attr('type','button')
                            .attr('data-toggle','modal')
                            .attr('data-target','#modal-confirm');
                    } else {
                        $btnSend
                            .attr('type','submit')
                            .removeAttr('data-toggle data-target');
                    }

                    let _class = resultCount > 0 ? 'text-danger' : 'text-success';
                    $resultCount.removeClass().addClass(_class).html('(risultati trovati: '+ resultCount +')')

                })
                .catch(function (error) {
                    alert('Si è verificato un errore nella comunicazione con il server');
                    $results.empty();
                    $resultCount.empty();
                    console.log(error);
                })
                .then(function () {
                    $loading.hide();
                });
        }


        $(document).ready(function () {

            let timer = null;
            $('#titolo').on('keyup',function (evt) {
                // Applica un timeout per non ingolfare il server
                clearTimeout(timer);
                timer = setTimeout(function(){
                    search(evt);
                },350);
            });

        });

    </script>
@endpush