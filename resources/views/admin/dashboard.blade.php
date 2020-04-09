@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-6">

                                <a href="{{ route('admin.users.index') }}"
                                   class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-users fa-fw"></i>
                                    Gestione Utenti
                                    <i class="fa fa-angle-right fa-fw"></i>
                                </a>

                            </div>
                            <div class="col-6">

                            </div>
                        </div>
                        <!-- /.row -->


                    </div>
                </div>
            </div>

        </div>


        <div class="row justify-content-center mt-4">

            <div class="col-sm-4">
                <div class="card shadow">
                    <div class="card-body p-3 d-flex flex-column align-items-center">

                        <h1>{{ $filmTotale }}</h1>
                        <div class="text-secondary">Totale Film</div>

                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card shadow">
                    <div class="card-body p-3 d-flex flex-column align-items-center">

                        <h1>{{ $filmNonVotatiTotale }}</h1>
                        <div class="text-secondary">Film Non Votati</div>

                    </div>
                </div>
            </div>

            <div class="w-100"></div>

            <div class="col-sm-4 mt-4">
                <div class="card shadow">
                    <div class="card-body p-3 d-flex flex-column align-items-center">

                        <h1>{{ $registiTotale }}</h1>
                        <div class="text-secondary">Totale Registi</div>

                    </div>
                </div>
            </div>


            <div class="col-sm-4 mt-4">
                <div class="card shadow">
                    <div class="card-body p-3 d-flex flex-column align-items-center">

                        <h1>{{ $usersTotale }}</h1>
                        <div class="text-secondary">Totale Utenti</div>

                    </div>
                </div>
            </div>


        </div>
        <!-- /.row -->


        <div class="row my-4 justify-content-center">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header">Altre Funzioni</div>
                    <div class="card-body p-0">

                        <div class="list-group list-group-flush">

                            <div class="list-group-item">
                                <h4>Aggiorna Valutazioni</h4>
                                <p>
                                    Se trovi discrepanze tra la valutazione nella Scheda del Film
                                    e quella nelle classifiche, forza l'aggiornamento delle valutazioni.
                                </p>
                                <div class="alert alert-info mb-3">
                                    <i class="fa fa-info-circle fa-fw"></i>
                                    <b>Attenzione</b>
                                    <div>L'operazione può impiegare diversi minuti. Non chiudere la pagina!</div>
                                </div>
                                <div class="pb-1">
                                    <a href="{{ route('admin.rating.updateBatch') }}"
                                       class="btn btn-light" onclick="return confirm('Proseguire?');">
                                        <i class="fa fa-users fa-fw"></i>
                                        Aggiorna Tutte le Valutazioni
                                        <i class="fa fa-angle-right fa-fw"></i>
                                    </a>
                                </div>
                            </div>


                            <div class="list-group-item">
                                <h4>Cestino Film</h4>
                                <p>
                                    Elenco delle schede film cestinate ma non ancora eliminate.
                                </p>
                                <button data-toggle="modal"
                                        data-target="#modal-cestino"
                                        class="btn btn-light">
                                    <i class="fa fa-trash fa-fw"></i>
                                    Mostra Cestino
                                    <i class="fa fa-angle-right fa-fw"></i>
                                </button>
                            </div>

                        </div>


                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->


    </div>




    <!-- Modal Cestino -->
    <div id="modal-cestino" class="modal fade" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cestino Film</h4>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">

                    <p>
                        Clicca sull'icona del cestino per <b>rimuovere definitivamente</b> il film dall'archivio
                    </p>

                    <div class="table-responsive">
                        <table
                                data-order='[[ 1, "asc" ]]'
                                class="dataTable table table-bordered table-sm table-striped">
                            <thead>
                            <tr>
                                <th class="align-middle" data-orderable="false">
                                    <small>#</small>
                                </th>
                                <th class="align-middle">
                                    Titolo
                                </th>
                                <th>
                                    Cestinato
                                </th>
                                <th data-orderable="false"></th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($trashed as $t)
                                <tr>
                                    <td class="fit align-middle">
                                        <small>{{ $loop->iteration }}</small>
                                    </td>
                                    <td>
                                        {{ $t->titolo }}
                                        <small class="d-block">
                                            ({{ $t->anno }})
                                        </small>
                                    </td>
                                    <td class="fit align-middle">
                                        {{ $t->deleted_at->diffForHumans() }}
                                    </td>
                                    <td class="fit align-middle">
                                        <form action="{{ route('admin.film.forcedelete', [$t->id]) }}"
                                              method="post"
                                              onsubmit="return confirm('Rimuovere definitivamente {{ strtoupper($t->titolo ) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <input name="id" id="id" type="hidden" value="{{ $t->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash fa-fw"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-->

@endsection
