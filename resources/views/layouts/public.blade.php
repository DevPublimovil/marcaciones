<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
    <style>

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
            color: #fff;
            padding: 0 25px;
            font-size: 13px;
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
    <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="wave hidden md:block bottom-0 left-0 fixed h-full">
        <path id="wavegradient" d="M-5.36,-25.16 C26.24,147.53 146.44,5.44 175.22,164.30 L0.00,150.00 L-10.44,-1.47 Z" style="stroke: none;"></path>
    </svg>
        @if (!Route::is('login'))
        <div class="flex-center position-ref">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a class="text-white" href="{{ route('inicio') }}">Login</a>
                    @endauth
                </div>
            @endif
        </div>
        @endif

        <main  id="app">
            <div class="flex">
                <div class="image flex flex-wrap content-center justify-end h-screen lg:w-1/2 md:w-1/2 sm:w-0 w-0">
                    <div class="w-4/6">
                        <img src="{{ asset('images/checklist.svg') }}" class="h h-64" alt="">
                    </div>
                </div>
                <div class="flex flex-wrap content-center justify-center lg:w-1/2 md:w-1/2 sm:w-full w-full">
                    <div class="text-gray-700 text-center w-full">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
        

    <!-- Scripts -->
   {{--  <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <script>
        const inputs = document.querySelectorAll('input');

        function focusFunc(){
            let parent = this.parentNode.parentNode;
            parent.classList.add('focus');
        }

        function blurFunc(){
            let parent = this.parentNode.parentNode;
            if(this.value == ""){
                parent.classList.remove('focus');
            }
        }

        inputs.forEach(input => {
            input.addEventListener('focus', focusFunc);
            input.addEventListener('blur', blurFunc);
        });
    </script>
    @yield('scripts')
</body>
</html>
