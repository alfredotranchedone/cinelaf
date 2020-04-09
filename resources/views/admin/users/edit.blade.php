@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span>Modifica Utente</span>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('admin.users.edit', [$user]) }}" method="post">
                            @csrf
                            @method('put')

                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text"
                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', $user->name) }}"
                                       placeholder="Username">
                                {!! $errors->first('name','<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text"
                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       name="email"
                                       id="email"
                                       value="{{ old('email',$user->email) }}"
                                       placeholder="Email">
                                {!! $errors->first('email','<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password"
                                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       name="password"
                                       id="password"
                                       value="{{ old('password') }}"
                                       placeholder="Password">
                                {!! $errors->first('password','<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="password_confirm">Conferma Password</label>
                                <input type="password"
                                       class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       value=""
                                       placeholder="Conferma Password">
                                {!! $errors->first('password_confirmation','<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            @if( ! (auth()->user()->isSuperAdmin() && $user->id == auth()->id()) )
                            <div class="form-group">
                                <label for="admin">Rendi Amministratore</label>
                                <select
                                        class="form-control {{ $errors->has('admin') ? 'is-invalid' : '' }}"
                                        name="admin"
                                        style="width: 75px"
                                        id="admin"
                                        {{ old('admin') }}>
                                    <option value="0" {{ old('admin', $user->is_super_admin) == 0 ? 'selected' : '' }}>NO</option>
                                    <option value="1" {{ old('admin', $user->is_super_admin) == 1 ? 'selected' : '' }}>SI</option>
                                </select>
                                {!! $errors->first('admin','<div class="invalid-feedback">:message</div>') !!}
                            </div>
                            @else
                                <input name="admin" id="admin" type="hidden" value="1" >
                            @endif

                            <div class="text-center pt-3 pb-3 bg-light rounded">
                                <button type="submit" class="btn btn-primary">
                                    Salva Utente
                                </button>
                            </div>

                        </form>

                    </div>

                    @if( ! (auth()->user()->isSuperAdmin() && $user->id == auth()->id()) )
                    <div class="card-footer">
                        <form action="{{ route('admin.users.delete', [$user]) }}"
                              onsubmit="return confirm('Proseguire con l\'eliminazione?');"
                              method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-user-plus fa-fw"></i>
                                Elimina Utente
                            </button>
                        </form>
                    </div>
                    @endif

                </div>
            </div>

        </div>


    </div>
@endsection
