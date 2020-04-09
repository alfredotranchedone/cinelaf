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
                        <span>Tutti i Film</span>
                    </div>

                    <div class="p-3 border-bottom bg-light">

                            <a class="btn btn-primary btn-sm mr-2"  href="{{ route('film.myratings') }}">
                                <i class="fa fa-check-circle fa-fw"></i>
                                Votati
                            </a>
                            <a class="btn btn-primary btn-sm mr-2" href="{{ route('film.mynotrated') }}">
                                <i class="fa fa-star fa-fw"></i>
                                Da Votare
                            </a>
                            <a class="btn btn-primary btn-sm mt-2 mt-sm-0" href="{{ route('film.noquorum') }}">
                                <i class="fa fa-ban fa-fw"></i>
                                Senza Quorum
                            </a>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="tblFilm" class="table dataTableFilm table-striped"
                                   data-ajax="{{ route('api.film.dt.all') }}">
                                <thead>
                                <tr>
                                    <th style="width: 75px"></th>
                                    <th>Titolo</th>
                                    <th>Anno</th>
                                    <th class="fit">Valutazione</th>
                                    <th style="width: 50px"></th>
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

            let watchlist = [];
            let $watchlistCounter = $('#headerWatchlistCounter');

            let watchlistLoad = function(){
                window.axios
                    .get(BASE_URL + '/api/watchlist/get')
                    .then( r => {
                        watchlist = r.data.data;
                    })
                    .catch(e => {
                        watchlist = [];
                        console.log('ERR', e);
                        alert('Errore caricamento watchlist...');
                    })
                    .then(function () {
                        return watchlist;
                    })
            };


            let renderWatchlist = function(type,filmId){

                $watchlistCounter.text(watchlist.length);

                let _template = '';
                switch (type) {
                    case 'added':
                        _template ='<div>' +
                        '   <button data-film-id="'+ filmId +'" class="remove-from-watchlist btn btn-success btn-sm">' +
                        '       <i class="fa fa-heart"></i> ' +
                        '       <i class="fa fa-check"></i> ' +
                        '   </button>' +
                        '</div>';
                        break;
                    case 'notAdded':
                    default:
                        _template ='<div>' +
                            '   <button data-film-id="'+ filmId +'" class="add-to-watchlist btn btn-primary btn-sm">' +
                            '       <i class="fa fa-heart"></i> ' +
                            '       <i class="fa fa-plus"></i> ' +
                            '   </button>' +
                            '</div>';
                        break;

                }

                return _template;
            };

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

                            let regista = [];
                            _.each(row.regista, function (item) {
                                regista.push(item.nome + ' ' + item.cognome);
                            });

                            let _html = '<a href="'+ BASE_URL +'/film/'+ row.id +'">'+ data +'</a>';
                            _html += '<div class="small">'+ _.join(regista, ', ') +'</div>';
                            return _html;
                        }
                    },
                    {
                        data: "anno",
                        className: "align-middle"
                    },
                    {
                        data: "valutazione",
                        className: "align-middle text-center"
                    },
                    {
                        data: "id",
                        className: "align-middle text-center",
                        render: function (data, type, row, meta) {
                            let _html = '';
                            let inList = false;
                            let _type = 'notAdded';
                            _.each(watchlist, function (item) {
                               if(item.film_id === data){
                                   inList = true;
                                   return false;
                               }
                            });

                            if(inList)
                                _type = 'added';

                            return renderWatchlist(_type, data);

                        }
                    }
                ]
            });

            /* Start */

            let table = $('.dataTableFilm').dataTable(dtOptions);

            /* Carica watchlist */
            watchlistLoad();

            /* Events */
            $('#tblFilm').on('click','.add-to-watchlist',function (evt) {

                let $this = $(evt.currentTarget);
                let filmId = $this.data('filmId');

                window.axios
                    .post(BASE_URL + '/api/watchlist/add',{
                        filmId: filmId
                    })
                    .then(r => {

                        watchlist.push({'film_id': filmId});

                        $this
                            .parent('div')
                            .parent('td')
                            .empty()
                            .html( renderWatchlist('added',filmId) );

                    })
                    .catch(e => {
                        console.log('ERR', e);
                        alert('Errore di comunicazione')
                    })

            });



            $('#tblFilm').on('click','.remove-from-watchlist',function (evt) {

                let $this = $(evt.currentTarget);
                let filmId = $this.data('filmId');

                window.axios
                    .post(BASE_URL + '/api/watchlist/remove',{
                        filmId: filmId
                    })
                    .then(r => {

                        _.remove(watchlist, item => item.film_id === filmId);

                        $this
                            .parent('div')
                            .parent('td')
                            .empty()
                            .html( renderWatchlist('notAdded',filmId) );

                    })
                    .catch(e => {
                        console.log('ERR', e);
                        alert('Errore di comunicazione')
                    })

            })

        });

    </script>
@endpush