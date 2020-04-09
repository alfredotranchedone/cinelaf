@extends('layouts.app')

@section('content')
    <div class="container">

        @include('inc.back',[
            'url' => route('home')
        ])

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <span>I Movie Da Votare</span>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped dataTableFilm"
                                   data-ajax="{{ $dataAjaxUrl }}">
                                <thead>
                                <tr>
                                    <th style="width: 75px"></th>
                                    <th>Titolo</th>
                                    <th class="fit">Anno</th>
                                    <th class="fit">Valutazione</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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

            let dtOptions = $.extend(true, {}, window.dataTableDefaultOptions, {
                "serverSide": true,
                "processing": true,
                "pageLength": 10,
                "order": [[3, 'desc']],
                "columns": [
                    {
                        data: "locandina",
                        orderable: false,
                        render: function (data, type, row, meta) {
                            let _img = data ? data : 'placeholder.jpg';
                            let _html = '<a href="'+ BASE_URL +'/film/'+ row.id +'">';
                            _html += '<img src="' + BASE_URL + '/img/locandina/' + _img + '" class="img-thumbnail" width="75" />';
                            _html += '</a>';
                            return _html;
                        }
                    },
                    {
                        data: "titolo",
                        className: "align-middle text-capitalize",
                        render: function (data, type, row, meta) {
                            let html =  '<div>' +
                                            row.titolo +
                                        '</div>' +
                                        '<a class="btn btn-link pl-0 mt-2 mb-3" href="'+ BASE_URL +'/film/'+ row.id +'">' +
                                            'Vai a Scheda Movie <i class="fa fa-angle-right fa-fw"></i>' +
                                        '</a>' +
                                        '<div class="input-group">' +
                                            '<select class="custom-select" data-film-titolo="'+ row.titolo +'" data-film-id="'+ row.id +'" style="max-width: 150px;">' +
                                            '   <option value="" disabled selected>Vota...</option>' +
                                            '   <option value="0.5">0.5</option>' +
                                            '   <option value="1">1</option>' +
                                            '   <option value="1.5">1.5</option>' +
                                            '   <option value="2">2</option>' +
                                            '   <option value="2.5">2.5</option>' +
                                            '   <option value="3">3</option>' +
                                            '   <option value="3.5">3.5</option>' +
                                            '   <option value="4">4</option>' +
                                            '   <option value="4.5">4.5</option>' +
                                            '   <option value="5">5</option>' +
                                            '</select>' +
                                            '<div class="input-group-append">' +
                                                '<button class="btn btn-primary vote">Vota Ora</button>' +
                                            '</div>' +
                                        '</div>';
                            return html;
                        }
                    },
                    {
                        data: "anno",
                        className: "align-middle"
                    },
                    {
                        data: "valutazione",
                        className: "align-middle text-center"
                    }
                ]
            });

            $('.dataTableFilm').dataTable(dtOptions);


            $('.dataTableFilm').on('click', '.vote', function (evt) {

                let $this = $(evt.currentTarget);
                let $select = $this.parents('.input-group').find('select');
                let voto = $select.val();
                let filmId = $select.data('filmId');
                let filmTitolo = $select.data('filmTitolo');

                if(!voto)
                    return;

                let _confirm = confirm('Confermi il voto di '+ voto +' al film "'+ filmTitolo +'"?');
                if(!_confirm)
                    return;



                window.axios.post(BASE_URL + '/api/rating/vote',{
                    filmId: filmId,
                    voto: voto
                }).then( r => {
                    $this.attr('disabled',true);
                    $select.attr('disabled',true);

                    alert('Voto registrato correttamente.','Vota Movie')

                })
                .catch(e => {
                    console.log('ERR', e);
                    alert('Si è verificato un errore')
                })

            });

        });

    </script>
@endpush