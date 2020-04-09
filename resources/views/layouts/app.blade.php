<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ strtoupper(config('app.name', 'Laravel')) }} - v{{ config('cinelaf.version') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha256-L/W5Wfqfa0sdBNIKN9cG6QA5F2qx4qICmU2VgLruv9Y="
          crossorigin="anonymous"/>
    <link href="{{ asset('theme/boldstrap/theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">

@guest
    <!-- guest -->
    @else
        <nav class="navbar navbar-expand-md navbar-dark bg-indigo shadow-sm">
            <div class="container">
                <a class="navbar-brand text-uppercase" href="{{ url('/') }}">
                    <i class="fa fa-theater-masks fa-fw"></i>
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item {{ (request()->is('home*')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="fa fa-home fa-fw"></i>
                                Home
                            </a>
                        </li>

                        <li class="nav-item {{ (request()->is('film/add*')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('film.add') }}">
                                <i class="fa fa-plus-circle fa-fw"></i>
                                {{ __('Add') }}
                            </a>
                        </li>

                        <li class="nav-item {{ (request()->is('film')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('film.index') }}">
                                <i class="fas fa-film fa-fw"></i>
                                {{ __('Movies') }}
                            </a>
                        </li>

                        <li class="nav-item {{ (request()->is('series*')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('series.index') }}">
                                <i class="fas fa-tv fa-fw"></i>
                                {{ __('Series') }}
                            </a>
                        </li>

                        <li class="nav-item {{ (request()->is('watchlist')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('watchlist.index') }}">
                                <i class="fa fa-heart fa-fw"></i>
                                Watchlist
                                <span id="headerWatchlistCounter" class="badge badge-danger">
                                    {{ session(config('cinelaf.sessions_key.watchlist.total')) ?? 0 }}
                                </span>
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->

                        @if(auth()->user()->isSuperAdmin())
                            <li class="nav-item {{ (request()->is('admin*')) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fa fa-shield-alt fa-fw"></i>
                                    {{ __('Administration') }}
                                </a>
                            </li>
                        @endif

                        <li class="nav-item {{ (request()->is('me*')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('me') }}">
                                <i class="fa fa-user fa-fw"></i>
                                {{ __('Profile') }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                                <i class="fa fa-sign-out-alt fa-fw"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    @endguest

    <main class="py-4">

        @if (session('msg'))

            <div class="container">
                <div class="alert alert-{{ session('msgType') }} alert-dismissible fade show mb-3" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    @if( session('msgType') == 'success')
                        <i class="fa fa-check mx-2"></i>
                    @endif
                    @if( session('msgType') == 'warning')
                        <i class="fa fa-exclamation-triangle mx-2"></i>
                    @endif
                    @if( session('msgType') == 'danger')
                        <i class="fa fa-times mx-2"></i>
                    @endif
                    @if( session('msgType') == 'info')
                        <i class="fa fa-info-circle mx-2"></i>
                    @endif

                    {{ session('msg') }}
                </div>
            </div>

        @endif

        {{-- Test in produzione per alternativa a session('msg') + session('msgType') --}}
        @include('inc.flash-message')

        @yield('content')
    </main>

    <footer class="mt-5 bg-white border-top">
        <div class="d-flex flex-column justify-content-center align-items-center py-3">
            <div class="small text-secondary">
                &copy; {{ strtoupper(config('app.name', 'Laravel')) }} |
                <span>
                        2020
                        @if(date('Y') > 2020)
                        - {{ date('Y') }}
                    @endif
                    </span>
            </div>
            <div class="small text-secondary">
                v{{ config('cinelaf.version') }}
            </div>
        </div>
    </footer>


    <!-- Modal Alert -->
    <div id="modal-alert" class="modal fade" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Attenzione!</h4>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-->

    <!-- Scripts -->
    <script type="text/javascript">
        window.BASE_URL = BASE_URL = {!! json_encode(url('/')) !!};
    </script>


    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')


</div>
</body>
</html>
