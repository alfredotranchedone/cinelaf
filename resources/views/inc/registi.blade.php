<?php
$registi = isset($registi) ? $registi : [];
?>
@forelse($registi as $regista)
    {{ $regista->nome }} {{ $regista->cognome }}@if(!$loop->last), @endif
@empty
    Nessun regista
@endforelse