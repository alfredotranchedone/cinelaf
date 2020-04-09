@if( $message = session('success'))
    <div class="container">
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <i class="fa fa-check mx-2"></i>
            {{ $message }}
        </div>
    </div>
@endif

@if( $message = session('warning'))
    <div class="container">
        <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <i class="fa fa-exclamation-triangle mx-2"></i>
            {{ $message }}
        </div>
    </div>
@endif

@if( $message = session('danger'))
    <div class="container">
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <i class="fa fa-times-circle mx-2"></i>
            {{ $message }}
        </div>
    </div>
@endif

@if( $message = session('info'))
    <div class="container">
        <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <i class="fa fa-info-circle mx-2"></i>
            {{ $message }}
        </div>
    </div>
@endif

