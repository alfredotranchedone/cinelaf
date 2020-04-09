@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span>Utenti</span>
                        <span class="badge badge-light">{{ $users->count() }}</span>
                    </div>

                    <div class="p-3">
                        <a href="{{ route('admin.users.add') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-user-plus fa-fw"></i>
                            Aggiungi Utente
                        </a>
                    </div>

                    <div class="card-body p-0 d-flex flex-column flex-sm-row justify-content-between align-items-center">

                        <table class="table">
                            <tr>
                                <th style="width: 40px">#</th>
                                <th>Username</th>
                                <th style="width: 50px"></th>
                            </tr>

                            @forelse($users as $user)
                                <tr>
                                    <td class="small align-middle">{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $user->name }}
                                        @if($user->isSuperAdmin())
                                            <i class="fa fa-user-shield fa-sm fa-fw text-success"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.edit',[$user]) }}">
                                            <i class="fa fa-edit fa-fw"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        Nessun utente
                                    </td>
                                </tr>
                            @endforelse
                        </table>

                    </div>
                </div>
            </div>

        </div>


    </div>
@endsection
