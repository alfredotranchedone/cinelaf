

<div class="list-group list-group-flush border-top rated-film">

    @forelse($ratings as $film)
        <a href="{{ route('film.show',[$film->id]) }}" class="list-group-item list-group-item-action border-bottom p-2 d-flex justify-content-start">

            <div class="ml-2 mr-2 d-flex align-items-center">
                <h1 class="p-0 m-0">{{ $film->valutazione }}</h1>
            </div>
            <div class="mr-2">
                <img src="{{ route('img.locandina', [ $film->locandina ]) }}" alt="" style="max-width: 75px">
            </div>
            <div>
                <div class="">
                    <i class="far fa-bookmark fa-fw"></i>
                    {{ $film->titolo }} <small>({{ $film->anno }})</small>
                </div>
                <div class="">
                    <i class="far fa-calendar-alt fa-fw"></i>
                    {{ optional($film->created_at)->format('d-m-Y') }}
                </div>
                <div class="">
                    <i class="far fa-user fa-fw"></i>
                    {{ $film->user->name }}
                </div>
            </div>

        </a>
    @empty
        <div class="p-2">
            Nessun film presente in archivio.
        </div>
    @endforelse

</div>