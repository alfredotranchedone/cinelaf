@extends('layouts.app')

@section('content')
    <div class="container">

        @include('inc.back',[
            'url' => route('film.index')
        ])



        <div class="row justify-content-center mt-4">

            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between bg-dark text-white">
                        <span>I Voti di {{ $user->name }}</span>
                        <div>
                            <span class="badge badge-light">{{ $ratingsTotal }}</span>
                        </div>
                    </div>

                    <div class="card-body p-0">

                        <div class="list-group list-group-flush">

                            <div class="list-group-item d-flex bg-light">
                                <div class="flex-even font-weight-bold">Film</div>
                                <div class="flex-even font-weight-bold text-center">Voto</div>
                                <div class="flex-even font-weight-bold text-right">Data Voto</div>
                            </div>

                            @forelse($ratings as $rating)
                                <div class="list-group-item d-flex">
                                    <div class="flex-even">{{ $rating->film->titolo }}</div>
                                    <div class="flex-even text-center">{{ $rating->voto }}</div>
                                    <div class="flex-even text-right">{{ $rating->updated_at->diffForHumans() }}</div>
                                </div>
                            @empty
                            @endforelse

                        </div>

                        @if(method_exists($ratings, 'links'))
                        <div class="d-flex justify-content-center px-3 mt-3">
                            {{ $ratings->links() }}
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>




    </div>
@endsection



@push('scripts')
    <script>

        $(document).ready(function () {


        });

    </script>
@endpush