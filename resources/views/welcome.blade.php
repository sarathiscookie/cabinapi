<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cabin API</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        @if(Auth::user()->usrlId === 1)
                            <a href="/admin/dashboard" class="btn btn-primary btn-flat">
                                @lang('admin.dashboard')
                            </a>
                        @elseif(Auth::user()->usrlId === 5)
                            <a href="/cabinowner/bookings" class="btn btn-primary btn-flat">
                                @lang('cabinowner.dashboard')
                            </a>
                        @elseif(Auth::user()->usrlId === 6)
                            <a href="/mountainschool/bookings" class="btn btn-primary btn-flat">
                                @lang('cabinowner.dashboard')
                            </a>
                        @endif
                        <a href="{{ route('logout') }}" class="btn btn-primary btn-flat"
                           onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                            @lang('cabinowner.logoutDashboard')
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <div class="login-logo">
                        <img src="/img/logo-huetten-holiday.png" alt="Huetten-Holiday.de">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

