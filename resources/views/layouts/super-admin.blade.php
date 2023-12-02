<!DOCTYPE html>
<html lang="en-US" class="no-js">

<head>
    <base href="{{ route('home') }}/">
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <!--[if IE]>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title>{{ strtoupper(config('app.name')) }}</title>

    <!--favicon icon-->
    <link rel="icon" href="img/favicon.png"/>

    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- font awesome css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

    <!--bootstrap min css-->
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <!--venobox min css-->
    <link rel="stylesheet" href="css/venobox.min.css"/>
    <!-- slick css -->
    <link rel="stylesheet" href="css/slick.css">
    <!-- select2 css -->
    <link rel="stylesheet" href="css/select2.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="css/style.css"/>

    @stack('styles')

</head>

<body class="admin-body">

<div class="admin-wrapper">

    <div class="admin-sidebar">
        <span class="d-block d-lg-none hamburger">
           <span></span>
       </span>
        <ul class="admin-menu">
            <li class="logo">
                <a href="{{ route('home') }}">
                    <img src="img/logo.png" alt="dashboard-logo"/>
                </a>
            </li>

            <li class=" {{ request()->route()->getName() == 'dashboard' ? ' active' : null }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>

            <li class="{{ strpos(request()->route()->getName(), 'settings.') !== false ? ' active' : null }}">
                <a href="{{ route('settings.index') }}">
                    <i class="fa fa-cog"></i>
                    General Settings
                </a>
            </li>

            <li class="{{ (strpos(request()->route()->getName(), 'ads.') !== false && ((request()->has('type') && request()->type == 'tutorial-screen') || (isset(request()->route()->parameter('ad')->type) && request()->route()->parameter('ad')->type == 'tutorial-screen'))) ? ' active' : null }}">
                <a href="{{ route('ads.index', ['type' => 'tutorial-screen']) }}">
                    <i class="fas fa-list"></i>
                    Tutorials Screen
                </a>
            </li>

            <li class="{{ (strpos(request()->route()->getName(), 'ads.') !== false && ((request()->has('type') && request()->type == 'launch-screen') || (isset(request()->route()->parameter('ad')->type) && request()->route()->parameter('ad')->type == 'launch-screen'))) ? ' active' : null }}">
                <a href="{{ route('ads.index', ['type' => 'launch-screen']) }}">
                    <i class="fas fa-rocket"></i>
                    Launch Screen Ad
                </a>
            </li>

            <li class="{{ (strpos(request()->route()->getName(), 'ads.') !== false && ((request()->has('type') && request()->type == 'banner') || (isset(request()->route()->parameter('ad')->type) && request()->route()->parameter('ad')->type == 'banner'))) ? ' active' : null }}">
                <a href="{{ route('ads.index', ['type' => 'banner']) }}">
                    <i class="fas fa-bullhorn"></i>
                    Ads Banner
                </a>
            </li>

            <li class="{{ (strpos(request()->route()->getName(), 'ads.') !== false && ((request()->has('type') && request()->type == 'logo') || (isset(request()->route()->parameter('ad')->type) && request()->route()->parameter('ad')->type == 'logo'))) ? ' active' : null }}">
                <a href="{{ route('ads.index', ['type' => 'logo']) }}">
                    <i class="fas fa-image"></i>
                    Ads Logo
                </a>
            </li>

            @isset($pages)
                @foreach($pages as $page)
                    <li class="{{ strpos(request()->route()->getName(), 'pages.') !== false && request()->route()->parameter('page')->type == $page->type ? ' active' : null }}">
                        <a href="{{ route('pages.edit', [$page]) }}">
                            <i class="fas fa-{{ $page->icon }}"></i>
                            {{ $page->universal_title }}
                        </a>
                    </li>
                @endforeach
            @endisset

            <li class="{{ strpos(request()->route()->getName(), 'users.') !== false ? ' active' : null }}">
                <a href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </li>

            <li class="{{ strpos(request()->route()->getName(), 'contacts.') !== false ? ' active' : null }}">
                <a href="{{ route('contacts.index') }}">
                    <i class="fas fa-envelope"></i>
                    Contacts
                </a>
            </li>

            <li class="{{ strpos(request()->route()->getName(), 'messages.') !== false ? ' active' : null }}">
                <a href="{{ route('messages.index') }}">
                    <i class="fas fa-envelope"></i>
                    Notification
                </a>
            </li>

            <li class="{{ strpos(request()->route()->getName(), 'l10n.translations.') !== false ? ' active' : null }}">
                <a href="{{ route('l10n.translations.index') }}">
                    <i class="fas fa-language"></i>
                    Translations
                </a>
            </li>

            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

        </ul>
    </div>


    <div class="admin-main-content">
        @yield('content')
    </div>
</div>


<!-- jQuary library -->
<script src="js/jquery.min.js"></script>
<!--bootstrap js-->
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- venobox -->
<script src="js/venobox.min.js"></script>

<!-- waypoint -->
<script src="js/slick.min.js"></script>

<!-- chart js -->
<script src="js/chart.min.js"></script>

<!-- select2 js -->
<script src="js/select2.min.js"></script>

<!-- jQuary activation -->
<script src="js/main.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@stack('scripts')

</body>

</html>

