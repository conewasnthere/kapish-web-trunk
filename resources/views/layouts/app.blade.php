<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset ($landing)
    <title>{{ config('app.name') }}</title>
    @else
    <title>@yield('title') - {{ config('app.name') }}</title>
    @endisset

    @yield('meta')
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    {!! HCaptcha::renderJs() !!}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3972374207754919" crossorigin="anonymous"></script>
    <script type="text/javascript">
        tadah = {
            baseUrl: "{{ config('app.url') }}"
        }

        @if (Auth::check())
            tadah.session = {
                userId: {{ Auth::user()->id }}
            }
        @endif
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

    @stack('head')

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @if ($theme != 'default')
    <link href="{{ asset('css/app_'.$theme.'.css?v='.rand(1,1000)) }}" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.4/css/pro.min.css" integrity="sha384-H+6ZmTqm/GHgxDH8fOr2HdvELiBHqnkV0chAMSvHG5SS+EfjjWivbrj6IyK+PjCB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <livewire:styles />
</head>

<body style="height: 100vh">
    <div class="d-flex {{isset($landing) ? : 'flex-column'}} h-100" id="app">
        @isset ($landing)
            @yield('content')
        @else
        <nav class="navbar navbar-expand-md navbar-dark bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img alt="{{ config('app.name') }}" src="{{ asset('images/logos/small.png') }}" width="30" height="30" class="d-inline-block align-top mr-2">{{ config ('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if (Auth::check())
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(1) == 'my' ? 'active' : ''}}" href="{{ route('my.dashboard') }}"><i class="fas fa-home mr-1"></i>{{ __('Home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(1) == 'servers' ? 'active' : ''}}" href="{{ route('servers.index') }}"><i class="fas fa-gamepad-alt mr-1"></i>{{ __('Servers') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(1) == 'catalog' ? 'active' : ''}}" href="{{ route('catalog.list') }}"><i class="fas fa-shopping-bag mr-1"></i>{{ __('Catalog') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(1) == 'users' ? 'active' : ''}}" href="{{ route('users.list') }}"><i class="fas fa-users mr-1"></i>{{ __('Users') }}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ Request::segment(1) == 'forum' ? 'active' : ''}}" href="{{ route('forum.index') }}"><i class="fas fa-comments-alt mr-1"></i>{{ __('Forums') }}</a>
                        </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @if (Auth::check())
                        @admin
                        <li class="nav-item mr-1">
                            <a class="nav-link" href="{{ route('admin.index') }}">
                                <i class="fas fa-hammer mr-1"></i>{{ __('Admin') }}
                                @php ($unapproved = \App\Models\Item::where('approved', 0)->count()) @endphp
                                @if($unapproved > 0)
                                <span class="badge badge-light badge-pill ml-1">
                                    {{ $unapproved }}
                                </span>
                                @endif
                            </a>
                        </li>
                        @endadmin
                        @endif
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt mr-1"></i>{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus mr-1"></i>{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item">
                            <span id="reward" class="navbar-text" data-toggle="tooltip" data-placement="bottom" data-original-title="0 hours, 0 minutes, and 0 seconds left until next reward" id="reward" data-tadah-started="{{ (new DateTime(Auth::user()->last_daily_reward, new DateTimeZone(config('app.timezone'))))->format('U') }}">
                                <img style="filter: opacity(75%);" src="/images/dahllor_white.png" alt="{{ Auth::user()->money }} {{ config('app.currency_name_multiple') }}" width="16" height="20"> {{ number_format(Auth::user()->money) }}
                            </span>
                        </li>

                        <li class="nav-item ml-1 dropdown">
                            <a id="navbarropdown" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img src="{{ \App\Http\Cdn\Thumbnail::static_image('blank.png') }}" data-tadah-thumbnail-id="{{ Auth::user()->id }}" data-tadah-thumbnail-type="user-headshot" class="rounded-circle mr-1 shadow-sm" width="25" id="navigation-headshot" data-tadah-thumbnail-id="{{ Auth::user()->id }}">
                                {{ Auth::user()->username }}
                                <i class="fas fa-cog ml-1 align-middle"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('users.profile', Auth::user()->id) }}">
                                    <i class="fas fa-id-card mr-1 align-middle"></i>Profile
                                </a>
                                <a class="dropdown-item" href="/character">
                                    <i class="fas fa-fw fa-user-edit mr-1 align-middle"></i>Character
                                </a>
                                @admin
                                <a class="dropdown-item" href="/admin">
                                    <i class="fas fa-hammer mr-1"></i>{{ __('Admin') }}
                                </a>
                                @endadmin
                                <a class="dropdown-item" href="{{ route('my.settings') }}">
                                    <i class="fas fa-fw fa-cog mr-1 align-middle"></i>{{ __('Settings') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger font-weight-bold" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-fw fa-sign-out-alt mr-1 align-middle"></i>Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @guest
        @else
            <div class="navbar-scroller navbar-expand-md navbar-dark navbar-second bg-dark py-0 shadow-sm" id="nav-items">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#secondary-navbar" aria-controls="secondary-navbar" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="secondary-navbar">
                        <ul class="navbar-nav">
                            <li class="nav-item" data-tadah-route="{{ route('users.profile', Auth::user()->id) }}">
                                <a class="nav-link" href="{{ route('users.profile', Auth::user()->id) }}">{{ __('Profile') }}</a>
                            </li>

                            <li class="nav-item" data-tadah-route="/character">
                                <a class="nav-link" href="/character">{{ __('Character') }}</a>
                            </li>

                            <li class="nav-item" data-tadah-route="friends">
                                <a class="nav-link" href="{{ route('my.friends') }}">
                                    {{ __('Friends') }}
                                    @if(Auth::user()->friendRequests()->count() > 0)
                                    <span class="badge badge-light badge-pill ml-1">
                                        {{Auth::user()->friendRequests()->count()}}
                                    </span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item" data-tadah-route="account">
                                <a class="nav-link" href="{{ route('my.settings') }}">{{ __('Account') }}</a>
                            </li>

                            @if (config('app.users_create_invite_keys'))
                            <li class="nav-item" data-tadah-route="/my/keys">
                                <a class="nav-link" href="{{ route('my.keys') }}">{{ __('Invites') }}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endguest

        @guest
        @else
        @if($alert && $alert['alert'] != null)
        <div class="alert border-0 {{$alert['color']}} shadow-sm rounded-0 p-1 text-center">
            @parsedown($alert['alert'])
        </div>
        @endif
        @endguest
        <noscript>
            <div class="alert alert-danger shadow-sm rounded-0" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                </svg>
                {{ __('Please enable JavaScript.') }}
            </div>
        </noscript>

        <div align="center" class="mt-2">
            <!-- Header and Footer -->
            <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-3972374207754919" data-ad-slot="1122913432"></ins>
        </div>

        <main class="py-4">
            @yield('content')
        </main>

        <div align="center" class="mt-2">
            <!-- Header and Footer -->
            <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-3972374207754919" data-ad-slot="1122913432"></ins>
        </div>

        <footer class="d-flex justify-content-center mt-auto">
            <div class="w-100 footer-dark mt-3">
                <div class="container pt-3 pb-3">
                    <ul class="nb-ul list-group list-group-horizontal nav">
                        <li class="flex-fill text-center"><a href="{{ route('document', 'service') }}" class="text-light fw-light h5 nav-item px-3 py-3 text-decoration-none">{{ __('Terms of Service') }}</a></li>
                        <li class="flex-fill text-center"><a href="{{ route('document', 'rules') }}" class="text-light fw-light h5 nav-item px-3 py-3 text-decoration-none">{{ __('Rules') }}</a></li>
                        <li class="flex-fill text-center"><a href="{{ route('document', 'privacy') }}" class="text-light fw-light h5 nav-item px-3 py-3 text-decoration-none">{{ __('Privacy Policy') }}</a></li>
                        <li class="flex-fill text-center"><a href="{{ route('document', 'credits') }}" class="text-light fw-light h5 nav-item px-3 py-3 text-decoration-none">{{ __('Credits') }}</a></li>
                        <li class="flex-fill text-center"><a href="{{ route('stats') }}" class="text-light fw-light h5 nav-item px-3 py-3 text-decoration-none">{{ __('Statistics') }}</a></li>
                    </ul>

                    <hr class="text-light">

                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <img style="opacity: .3;" alt="{{ config('app.name') }}" src="{{ asset('images/logos/footer_full.png') }}" height="80" class="d-flex justify-content-center">
                        </div>
                        <div class="py-md-0 py-3 col-md col-auto text-md-start text-start footer-text text-light align-self-center">
                            <b>©</b> {{ \Carbon\Carbon::now()->year }} {{ config('app.name') }}. {{ config('app.name') }} is a not-for-profit private community. {{ config('app.name') }} is not a part of any corporation.<br> Built with <i class="font-weight-bold mx-1 fa fa-heart"></i> and <i class="font-weight-bold mx-1 fab fa-laravel"></i> Laravel
                        </div>
                        <div class="col-auto row align-self-center text-md-start text-center">
                            <div class="d-inline-flex col-auto justify-content-center">
                                <a class="d-flex text-decoration-none text-white social-footer-link" href="https://discord.gg/kapish">
                                    <i class="fab fa-2x fa-discord mr-1"></i>
                                </a>
                            </div>
                            <!-- <div class="d-inline-flex col-auto justify-content-center">
                                <a class="d-flex text-decoration-none text-white social-footer-link" href="https://twitter.com/">
                                    <i class="fab fa-2x fa-twitter mr-1"></i>
                                </a>
                            </div>
                            <div class="d-inline-flex col-auto justify-content-center">
                                <a class="d-flex text-decoration-none text-white social-footer-link" href="https://www.youtube.com/channel/">
                                    <i class="fab fa-2x fa-youtube mr-1"></i>
                                </a>
                            </div> -->
                            <div class="d-inline-flex col-auto justify-content-center">
                                <a class="d-flex text-decoration-none text-white social-footer-link text-white social-footer-link" href="https://gitlab.com/tadah">
                                    <i class="fab fa-2x fa-gitlab mr-1"></i>
                                </a>
                            </div>
                            <div class="d-inline-flex col-auto justify-content-center">
                                <a class="d-flex text-decoration-none text-white social-footer-link" href="mailto:{{ config('app.mailing_address') }}">
                                    <i class="fa fa-2x fa-envelope mr-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        @endisset
    </div>
    <livewire:scripts />
    @yield('scripts')
    <script>
        [].forEach.call(document.querySelectorAll('.adsbygoogle'), function() {
            (adsbygoogle = window.adsbygoogle || []).push({});
        });
    </script>
</body>

</html>
