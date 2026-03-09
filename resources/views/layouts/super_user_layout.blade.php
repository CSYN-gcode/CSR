@php
// session_start();
$isLogin = false;
if (isset($_SESSION['rapidx_user_id'])) {
    $isLogin = true;
}

$isAuthorized = false;
$user_level = 0;
@endphp

@if ($isLogin)
    @if ($_SESSION['rapidx_user_level_id'] == 1 || $_SESSION['rapidx_user_level_id'] == 2 || $_SESSION['rapidx_user_level_id'] == 3 || $_SESSION['rapidx_user_level_id'] == 4 || $_SESSION['rapidx_user_level_id'] == 5)
        <!-- 1-Super User, 2-Administrator, 3-User, 4-QAD Admin, 5-Other Section -->
        @if (count($_SESSION['rapidx_user_accesses']) > 0)
            <!-- Count the rapidx_user_accesses session based on RapidX session -->
            @for ($index = 0; $index < count($_SESSION['rapidx_user_accesses']); $index++)
                <!-- Loop the rapidx_user_accesses session based on RapidX session -->
                <!--
                    You will see the module_id on the table inside modules(table) under db_rapidx(database) since Customer Claim Database System id is 11
                    you are free to change below module_id equals to your module_id
                -->
                @if ($_SESSION['rapidx_user_accesses'][$index]['module_id'] == 51)
                    <!-- 51- PTHS -->
                    @php
                        $isAuthorized = true;
                        $user_level = $_SESSION['rapidx_user_accesses'][$index]['user_level_id']; // Collect the user_level_id

                        // Check if the main system sent a user ID
                        // $currentUser = \App\Models\User::where('rapidx_user_id', $_SESSION['rapidx_user_id'] ?? null)->first();
                        
                    @endphp
                    @break
                @endif
            @endfor
        @endif

        @if (!$isAuthorized)
            <script type="text/javascript">
                window.location = '../RapidX/';
            </script>
        @endif
    @else
        <script type="text/javascript">
            window.location = '../RapidX/';
        </script>
    @endif

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>PTHS | @yield('title')</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <link rel="shortcut icon" type="image/png" href="{{ asset('public/images/favicon1.ico') }}">

            <!-- CSS LINKS -->
            @include('shared.css_links.css_links')
            <style>
                .modal-xl-custom {
                    width: 70% !important;
                    min-width: 70% !important;
                }

            </style>
        </head>

        <body class="hold-transition sidebar-mini">

            <div class="wrapper">
                @include('shared.pages.header')

                <script type="text/javascript">
                let _token = "{{ csrf_token() }}";
                </script>

                @include('shared.pages.super_user_nav')
                @include('shared.js_links.js_links')
                @yield('js_content')
                @php
                    // print_r( Auth::user()->id );
                @endphp
                @yield('content_page')
                @include('shared.pages.footer')
            </div>
            <script type="text/javascript">
                $(document).ready(function () {

                });
            </script>
        </body>
    </html>
@else
    <script type="text/javascript">
        window.location = "../RapidX/";
    </script>
@endif
