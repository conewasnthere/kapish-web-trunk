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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3972374207754919" crossorigin="anonymous"></script>
    <script type="text/javascript">tadah = {}; window.tadah = {}; window.tadah.route = "{{ \Request::route()->getName() }}"; window.tadah.domain = "{{ config('app.url') }}"; window.tadah.loggedIn =@guest false @else true @endguest;</script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

    @stack('head')

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @if ($theme != 'default')
    <link href="{{ asset('css/app_'.$theme.'.css') }}" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.4/css/pro.min.css" integrity="sha384-H+6ZmTqm/GHgxDH8fOr2HdvELiBHqnkV0chAMSvHG5SS+EfjjWivbrj6IyK+PjCB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body>
    <div id="app">
        <noscript>
            <div class="alert alert-danger shadow-sm rounded-0" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                </svg>
                {{ __('Please enable JavaScript.') }}
            </div>
        </noscript>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('scripts')
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</body>

</html>