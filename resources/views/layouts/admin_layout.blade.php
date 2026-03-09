@php
session_start();
$isLogin = false;
if (isset($_SESSION['rapidx_user_id'])) {
    $isLogin = true;
}

$isAuthorized = false;
$user_level = 0;
@endphp

@if ($isLogin)
    @if ($_SESSION['rapidx_user_level_id'] == 5)
        <!-- 1-Super User, 2-Administrator, 3-User, 4-QAD Admin, 5-Other Section -->
        @if (count($_SESSION['rapidx_user_accesses']) > 0)
            <!-- Count the rapidx_user_accesses session based on RapidX session -->
            @for ($index = 0; $index < count($_SESSION['rapidx_user_accesses']); $index++)
                <!-- Loop the rapidx_user_accesses session based on RapidX session -->
                @if ($_SESSION['rapidx_user_accesses'][$index]['module_id'] == 16)
                    <!-- 11-Customer Claim Database System Module -->
                    @php
                        $isAuthorized = true;
                        $user_level = $_SESSION['rapidx_user_accesses'][$index]['user_level_id']; // Collect the user_level_id
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
@endif

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> PTHS | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('public/images/favicon1.ico') }}">

    <style>
        .modal-xl-custom {
            width: 95% !important;
            min-width: 90% !important;
        }

    </style>
    <!-- CSS LINKS -->
    @include('shared.js_links.js_links')

    @include('shared.css_links.css_links')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('shared.pages.header')
        @include('shared.pages.admin_nav')
        @yield('content_page')

        @include('shared.pages.footer')
    </div>

    <!-- JS LINKS -->
    @yield('js_content')
    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>
</body>

</html>
@else
<script type="text/javascript">
    window.location = "/";
</script>
@endif
