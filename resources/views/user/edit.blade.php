@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center mt-4">
            <div class="col-sm-8">
                <div class="card shadow">

                    <div class="d-flex px-3 pt-2 pb-2 justify-content-center align-items-center text-secondary border-bottom">
                        <div>
                            <i class="fa fa-user-circle mt-1" style="font-size: 1.5em"></i>
                        </div>
                        <div class="font-weight-light text-capitalize mx-3" style="font-size: 1.6em">
                            {{ auth()->user()->name }}
                        </div>
                    </div>

                    <div class="card-body p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center">

                        <div class="d-flex flex-even justify-content-center align-items-baseline">
                            <h1 class="mb-0 mr-2 font-weight-bold">{{ $myAvg }}</h1>
                            <div class="text-secondary">media voti</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span>Modifica Profilo</span>
                    </div>

                    <div class="p-3 border-bottom">
                        <a href="{{ route('home') }}" class="btn btn-light">
                            <i class="fa fa-angle-left fa-fw"></i>
                            Annulla
                        </a>
                    </div>


                    <div class="card-body">

                        <form action="{{ route('me.save', [$user]) }}" method="post">
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


                            <div class="text-center pt-3 pb-3 bg-light rounded">
                                <button type="submit" class="btn btn-primary">
                                    Salva Modifiche
                                </button>
                            </div>

                        </form>

                    </div>


                </div>
            </div>

        </div>


    </div>
@endsection
