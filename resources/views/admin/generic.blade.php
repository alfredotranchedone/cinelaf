@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center mt-4">

            <div class="col-sm-8">
                <div class="card shadow">
                    <div class="card-body">

                        {!! $output  !!}

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
