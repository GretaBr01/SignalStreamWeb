<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, 
              user-scalable=no">
        
        <!-- Fogli di stile -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ url('/') }}/css/style.css" rel="stylesheet">

        <!-- jQuery e plugin JavaScript  -->
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        

        <!-- Custom jQuery and Javascript scripts -->
        <script src="{{ url('/') }}/js/paginationScript.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
            <div class="container-fluid px-4">
                
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                    <strong>{{ __('messages.title') }}</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item"><a href="{{ route('setLang', ['lang' => 'en']) }}" class="nav-link"><img src="{{ url('/') }}/img/flags/en.png" width="30"/></a></li>
                        <li class="nav-item"><a href="{{ route('setLang', ['lang' => 'it']) }}" class="nav-link"><img src="{{ url('/') }}/img/flags/it.png" width="25"/></a></li>
                        
                    </ul>
                    
                    <ul class="navbar-nav gap-3">
                        <li class="nav-item">
                        <a class="nav-link" href="{{ route('howitworks') }}">{{ __('messages.how_it_works') }}</a>
                        </li>

                        @if(auth()->check())
                            <li class="nav-item">
                            <a class="nav-link" href="{{ route('series.index') }}">{{ __('messages.work_space') }}</a>
                            </li>

                            <li class="nav-item"><i>{{ auth()->user()->name }}</i>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                    <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;">
                                        <i class="bi bi-box-arrow-right"></i>
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('messages.btn_login') }}</a>
                            </li>
                            <li class="nav-item">
                            <a class="btn btn-outline-primary" href="{{ route('register') }}">{{ __('messages.register') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('body')
    </body>

</html>