

@if(!$film->watchlist)
<a href="{{ route('watchlist.add', [$film->id]) }}" class="btn btn-outline-primary btn-sm">
    <i class="fa fa-heart fa-fw"></i>
    Aggiungi alla Watchlist
</a>
@else
<div>
    <span class="badge badge-light p-2 mb-2">
        <i class="fa fa-heart fa-fw text-danger"></i>
        Il Film è nella tua Watchlist
    </span>
</div>
<div class="ml-3 ml-sm-0 text-center text-sm-left">
    <form action="{{ route('watchlist.remove',[$film->id]) }}" method="post">
        @csrf
        <input name="filmId" id="filmId" type="hidden" value="{{ $film->id }}" >
        <button type="submit" class="btn btn-link btn-sm">
            <i class="fas fa-heart-broken fa-fw"></i>
            Rimuovi dalla Watchlist
        </button>
    </form>
</div>
@endif