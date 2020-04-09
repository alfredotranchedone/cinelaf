
<?php
$url = isset($url) ? $url : (url()->previous() ?? route('home'));
?>

<div class="row">
    <div class="col-sm-8 mx-auto">
        <a href="{{ $url }}" class="btn btn-primary btn-sm">
            <i class="fa fa-angle-left fa-fw"></i>
            Indietro
        </a>
    </div>
</div>
<!-- /.row -->