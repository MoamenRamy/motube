<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>motube</title>
    {{-- bootstrap --}}
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://fastly.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://fastly.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    {{-- font awesome --}}
    <script src="https://kit.fontawesome.com/160daa7df6.js" crossorigin="anonymous"></script>

    {{-- jetstream css settings --}}
    @vite(['resources/css/app.css'])

    @vite(['resources/css/style.css'])

    <link rel="stylesheet" href="{!! asset('theme/css/sb-admin-2.css') !!}">

</head>
<body>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light bg-white">
            <a class="navbar-brand" href="#">MoTube</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                        <a href="{{ route('main') }}" class="nav-link">
                            <i class="fas fa-home">
                                Home
                            </i>
                        </a>
                    </li>

                    @auth
                    <li class="nav-item {{ request()->is('history') ? 'active' : '' }}">
                        <a href="{{route('history')}}" class="nav-link">
                            <i class="fas fa-history">
                                History
                            </i>
                        </a>
                    </li>
                    <li class="nav-item" {{ request()->is('videos/create*') ? 'active' : '' }}>
                        <a href="{{route('videos.create')}}" class="nav-link">
                            <i class="fas fa-upload">
                                Upload video
                            </i>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('videos') ? 'active' : '' }}">
                        <a href="{{route('videos.index')}}" class="nav-link">
                            <i class="far fa-play-circle">
                                My videos
                            </i>
                        </a>
                    </li>
                    @endauth

                    <li class="nav-item {{ request()->is('channel*') ? 'active' : '' }}">
                        <a href="{{route('channels.index')}}" class="nav-link">
                            <i class="fas fa-film">
                                Channel
                            </i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <div class="topbar" style="z-index:1">
                        @auth
                            <!-- Nav Item - Alerts -->
                            <li class="nav-item dropdown no-arrow alert-dropdown mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw fa-lg"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter notif-count" data-count="{{ App\Models\Alert::where('user_id', Auth::user()->id)->first()->alert }}">{{ App\Models\Alert::where('user_id', Auth::user()->id)->first()->alert }}</span>
                                </a>
                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-left mt-2"
                                    aria-labelledby="alertsDropdown">
                                    <div class="alert-body">

                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500" href="{{route('all.notifications')}}">show all notifications</a>
                                </div>
                            </li>
                        @endauth
                    </div>
                    @guest
                        <li class="nav-item mt-2">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item mt-2">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown justify-content-left mt-2">
                            <a id="navbarDropdown" class="nav-link" href="#" data-toggle="dropdown">
                                <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-left px-2 text-left mt-2">
                                @can('update-videos')
                                    <a href="{{ route('admin.index') }}" class="dropdown-item">administration</a>
                                @endcan
                                <div class="pt-4 pb-1 border-t border-gray-200">
                                    <div class="flex items-center px-4">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <div class="flex-shrink-0 mr-3">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                            </div>
                                        @endif

                                        <div class="mr-3">
                                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                        </div>
                                    </div>

                                    <div class="mt-3 space-y-1">
                                        <!-- Account Management -->
                                        <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="dropdown-item ">
                                            {{ __('profile') }}
                                        </x-responsive-nav-link>

                                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                            <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')" class="dropdown-item ">
                                                {{ __('api_token') }}
                                            </x-responsive-nav-link>
                                        @endif

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-responsive-nav-link href="{{ route('logout') }}" class="dropdown-item"
                                                        onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                                {{ __('logout') }}
                                            </x-responsive-nav-link>
                                        </form>

                                        <!-- Team Management -->
                                        @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                            <div class="border-t border-gray-200"></div>

                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('manage_team') }}
                                            </div>

                                            <!-- Team Settings -->
                                            <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')" class="dropdown-item">
                                                {{ __('team_settings') }}
                                            </x-responsive-nav-link>

                                            {{-- @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                                <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')" class="dropdown-item">
                                                    {{ __('new_team') }}
                                                </x-responsive-nav-link>
                                            @endcan --}}

                                            <div class="border-t border-gray-200"></div>

                                            <!-- Team Switcher -->
                                            <div class="block px-4 py-2 text-xs text-gray-400" class="dropdown-item">
                                                {{ __('team_switch') }}
                                            </div>

                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" component="responsive-nav-link" />
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>
        <main class="py-4">

            @if(Session::has('success'))
            <div class="p-3 mb-2 bg-success text-white rounded mx-auto col-8">
                <span class="text-center">{{ session('success') }}</span>
            </div>
            @endif

            @yield('content')
        </main>
    </div>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('a79f5c0103d5bba8e980', {
            cluster: 'mt1'
        });

    </script>

    <script src="{{asset('js/pushNotifications.js')}}"></script>
    <script src="{{asset('js/failedNotifications.js')}}"></script>

    <script>
        var token = '{{ Session::token() }}';
        var urlNotify = '{{ route('notification') }}';

        $('#alertsDropdown').on('click', function(event) {
            event.preventDefault();
            var notificationsWrapper = $('.alert-dropdown');
            var notificationsToggle = notificationsWrapper.find('a[data-toggle]');
            var notificationsCountElem = notificationsToggle.find('span[data-count]');

            notificationsCount = 0;
            notificationsCountElem.attr('data-count', notificationsCount);
            notificationsWrapper.find('.notif-count').text(notificationsCount);
            notificationsWrapper.show();

            $.ajax({
                method: 'POST',
                url: urlNotify,
                data: {
                    _token: token
                },
                success : function(data) {
                    var resposeNotifications = "";
                    $.each(data.someNotifications , function(i, item) {
                        var responseDate = new Date(item.created_at);
                        var date = responseDate.getFullYear()+'-'+(responseDate.getMonth()+1)+'-'+responseDate.getDate();
                        var time = responseDate.getHours() + ":" + responseDate.getMinutes() + ":" + responseDate.getSeconds();

                        if (item.sucess) {
                            resposeNotifications += '<a class="dropdown-item d-flex align-items-center" href="#">\
                                                        <div class="mr-3">\
                                                            <div class="icon-circle bg-secondary">\
                                                                <i class="far fa-bell text-white"></i>\
                                                            </div>\
                                                        </div>\
                                                        <div>\
                                                            <div class="small text-gray-500">'+date+' hour '+time+'</div>\
                                                            <span>Congratulations, your video has been processed <b>'+item.notification+'</b> successfuly</span>\
                                                        </div>\
                                                    </a>';
                        } else {
                            resposeNotifications += '<a class="dropdown-item d-flex align-items-center" href="#">\
                                                        <div class="mr-3">\
                                                            <div class="icon-circle bg-secondary">\
                                                                <i class="far fa-bell text-white"></i>\
                                                            </div>\
                                                        </div>\
                                                        <div>\
                                                            <div class="small text-gray-500">'+date+' hour '+time+'</div>\
                                                            <span>Unfortunately, an unexpected error occurred while processing the video <b>'+item.notification+'</b> Please upload it again</span>\
                                                        </div>\
                                                    </a>';
                        }

                        $('.alert-body').html(resposeNotifications);
                    });
                }
            });
        });
    </script>
    @yield('script')
</body>
</html>
