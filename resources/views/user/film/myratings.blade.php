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
                        <span>I Miei Voti</span>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table dataTableFilm"
                                   data-ajax="{{ route('api.film.dt.myratings') }}">
                                <thead>
                                <tr>
                                    <th style="width: 75px"></th>
                                    <th>Titolo</th>
                                    <th class="fit">Voto</th>
                                    <th>Data Voto</th>
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
                            return '<a class="text-decoration-none" href="'+ BASE_URL +'/film/'+ row.film.id +'">'+ row.film.titolo +' <small>('+ row.film.anno +')</small></a>';
                        }
                    },
                    {
                        data: "voto",
                        className: "align-middle"
                    },
                    {
                        data: "data_voto",
                        className: "align-middle fit",
                        render: function (data, type, row, meta) {
                            return '<small>'+ data +'</small>';
                        }
                    }
                ]
            });

            $('.dataTableFilm').dataTable(dtOptions);

        });

    </script>
@endpush