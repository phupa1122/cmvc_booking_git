<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">

    <!-- CSS for DataTables -->
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- Fonts and additional CSS -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <style>
        body {
            background-color: #f2f2f2;
            font-family: 'Kanit', sans-serif;
        }

        h1,
        h2 {
            text-align: center;
            color: white;
            font-family: 'Kanit', sans-serif;
        }

        label,
        tr,
        td {
            font-size: 16px;
            font-family: 'Kanit', sans-serif;
        }

        .custom-checkbox input[type="checkbox"] {
            transform: scale(1.5);
            /* ปรับขนาดตามต้องการ เช่น 1.5 */
            margin-right: 15px;
            /* เพิ่มช่องว่างด้านขวา */
            border-color: #A02334;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #A02334;
            /* สีแดงเข้ม */
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            background-color: white;

        }

        .custom-checkbox input[type="checkbox"]:checked {
            background-color: #A02334;
            /* สีแดงเมื่อถูกเลือก */
            border-color: #A02334;
        }

        .ui-autocomplete .unavailable {
            background-color: #f8d7da;
            /* Light red background */
            color: #A02334;
            /* Dark red text */
        }

        .card-header {
            background-color: #A02334;
        }

    </style>

</head>

<body>
    <div id="app" class="element">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container ">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Booking CMVC
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @guest
                            @if (Route::has('login'))
                            @endif

                            @if (Route::has('register'))
                            @endif
                        @else
                            @if (auth()->user()->is_admin)
                                <li class="nav-item dropdown">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('bookings.create') }}">จอง</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/meeting-rooms">ห้องประชุม</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('feedback.index') }}">ข้อเสนอแนะ</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('users.index') }}">จัดการผู้ใช้</a>
                                    </li>
                                </li>
                            @else
                            <li class="nav-item dropdown">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings.create') }}">จอง</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/meeting-rooms">ห้องประชุม</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('feedback.index') }}">ข้อเสนอแนะ</a>
                                </li>
                            </li>
                            @endif
                        @endguest
                    </ul>



                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">เข้าสู่ระบบ</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">สมัครสมาชิก</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    สวัสดี , {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if (auth()->user()->is_admin)
                                        <a class="dropdown-item" href="{{ route('admin.home') }}">Dashboard</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('home') }}">Dashboard</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        ออกจากระบบ
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

        <div class="container py-4 px-5">
            @yield('content')
        </div>

        <div>
            @yield('showMeetingRoom')

        </div>
    </div>
    @stack('scripts')
</body>

</html>
