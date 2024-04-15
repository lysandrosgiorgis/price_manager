<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('includes.head')
</head>
<body>
    <div id="app">
        <nav id="sidebarLeft" class="bg-white border-end">
            <a class="home-link border-bottom" href="{{ url('/home') }}">
                <span class="fa fa-home fs-4 me-3"></span>
                <span class="menu-link-text">{{ config('app.name', 'Laravel') }}</span>
            </a>
            <span class="menu-link"
                  data-bs-toggle="collapse"
                  data-bs-target="#menuCatalog"
                  aria-controls="#menuCatalog"
                  aria-expanded="false">
                <span class="fa fa-tags me-3"></span>
                <span class="menu-link-text">{{ __('Catalog') }}</span>
                 <span class="fa fa-chevron-right small-right"></span>
            </span>
            <div class="collapse p-2 bg-light child-container" id="menuCatalog">
                <a href="{{ route('catalog.product') }}" class="menu-link child-link">
                    {{ __('Products') }}
                </a>
                <a href="{{ route('catalog.category') }}" class="menu-link child-link">
                    {{ __('Categories') }}
                </a>
            </div>
            <span class="menu-link"
                  data-bs-toggle="collapse"
                  data-bs-target="#menuCompetition"
                  aria-controls="#menuCompetition"
                  aria-expanded="false">
                <span class="fa fa-cog me-3"></span>
                <span class="menu-link-text">{{ __('Competition') }}</span>
                <span class="fa fa-chevron-right small-right"></span>
            </span>
            <div class="collapse p-2 bg-light" id="menuCompetition">
                <a href="{{ route('competition.company') }}" class="menu-link child-link">
                    {{ __('Companies') }}
                </a>
                <a href="{{ route('competition.category') }}" class="menu-link child-link">
                    {{ __('Categories') }}
                </a>
                <a href="{{ route('competition.product') }}" class="menu-link child-link">
                    {{ __('Products') }}
                </a>
            </div>
        </nav>
        <div id="main-page">
            <div id="top" class="navbar sticky-top bg-white p-2 justify-content-start border-bottom ">
                <button type="button" class="btn btn-light border lh-1 ph-1" title="{{ __('Menu') }}" onclick="document.body.classList.toggle('activeMenu');">
                    <span class="fa fa-bars fs-4"></span>
                </button>
                <!-- Right Side Of Navbar -->
                <div class="ms-auto d-flex">
                    <a href="#" class="dropdown-toggle header-icon me-3" data-toggle="dropdown">
                        <i class="far fa-envelope" aria-hidden="true"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <a href="#" class="dropdown-toggle header-icon me-3" data-toggle="dropdown">
                        <i class="far fa-bell" aria-hidden="true"></i>
                        <span class="label label-warning">4</span>
                    </a>
                    <a href="#" class="dropdown-toggle header-icon me-3" data-toggle="dropdown">
                        <i class="far fa-flag" aria-hidden="true"></i>
                        <span class="label label-danger">4</span>
                    </a>
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <div class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </div>
                        @endif

                        @if (Route::has('register'))
                            <div class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </div>
                        @endif
                    @else
                        <div class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

            </div>
            <main class="p-3">
                @yield('content')
            </main>
        </div>
    </div>
    @include('includes.footer')
    @stack('beforeBody')
</body>
</html>
