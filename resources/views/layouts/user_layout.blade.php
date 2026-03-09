@auth
    @if (Auth::user()->is_password_changed == 0)
        <script type="text/javascript">
            window.location = "{{ url('change_pass_view') }}";
        </script>
    @endif

    @if (Auth::user()->status == 2)
        <script type="text/javascript">
            window.location = "{{ url('login') }}";
        </script>
    @endif

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Key 4 Monitoring | @yield('title')</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="{{ asset('public/images/favicon.ico') }}">

        <style>
            .modal-xl-custom {
                width: 95% !important;
                min-width: 90% !important;
            }

        </style>
        <!-- CSS LINKS -->
        @include('shared.css_links.css_links')
    </head>

    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            @include('shared.pages.header')
            @include('shared.pages.user_nav')
            @yield('content_page')
            @include('shared.pages.footer')
        </div>

        <!-- JS LINKS -->
        @include('shared.js_links.js_links')
        @yield('js_content')
        @include('shared.pages.common')
        <script type="text/javascript">
            $(document).ready(function () {
            LoadSession();

            function LoadSession() {
                $.ajax({
                    url: 'get_user_department',
                    method: 'get',
                    dataType: 'json',
                    success: function (response) {
                        result = '';
                        let departmentID = response['department_id'];
                        let departments = response['departments_authorized'];
                        let auth = response['auth'];

                        console.log(departmentID);
                        console.log(departments);
                        console.log(auth);

                        if(auth != 1) {
                            if(jQuery.inArray(departmentID, departments) != -1) {
                                console.log('in array');

                                $('#energyConsumption').css('display', 'block');
                                $('.waterConsumption').css('display', 'block');
                                $('.paperConsumptionSG').css('display', 'block');

                                $('.paperConsumptionTS').css('display', 'block');
                                $('.paperConsumptionYF').css('display', 'block');
                                $('.paperConsumptionPPS').css('display', 'block');
                                $('.paperConsumptionCN').css('display', 'block');

                                $('#waternav').css('display', 'block');
                                $('#energyNav').css('display', 'block');
                            } else {
                                $('#energyConsumption').css('display', 'none');
                                $('.waterConsumption').css('display', 'none');
                                $('.paperConsumptionSG').css('display', 'none');

                                $('.paperConsumptionTS').css('display', 'block');
                                $('.paperConsumptionYF').css('display', 'block');
                                $('.paperConsumptionPPS').css('display', 'block');
                                $('.paperConsumptionCN').css('display', 'block');
                                // $('#paperConsumptionTS').addClass('active');

                                $('#waternav').css('display', 'none');
                                $('#energyNav').css('display', 'block');
                            }
                        }

                    }
                });
            }
        });
        </script>
    </body>

    {{-- @if (Session::get('authorized') == 0)
        @if (in_array(Session::get('department_id'), Session::get('departments')))
        <script type="text/javascript">
            setInterval(() => {
                console.log('admin');
                $('#energyConsumption').css('display', 'block');
                $('.waterConsumption').css('display', 'block');
                $('.paperConsumptionSG').css('display', 'block');

                $('.paperConsumptionTS').css('display', 'block');
                $('.paperConsumptionYF').css('display', 'block');
                $('.paperConsumptionPPS').css('display', 'block');
                $('.paperConsumptionCN').css('display', 'block');
                // $('#paperConsumptionTS').addClass('active');

                $('#waternav').css('display', 'block');
                $('#energyNav').css('display', 'block');
            }, 500);


        </script>

            @else
                <script type="text/javascript">
                setInterval(() => {
                    console.log('not admin');

                        $('#energyConsumption').css('display', 'none');
                        $('.waterConsumption').css('display', 'none');
                        $('.paperConsumptionSG').css('display', 'none');

                        $('.paperConsumptionTS').css('display', 'block');
                        $('.paperConsumptionYF').css('display', 'block');
                        $('.paperConsumptionPPS').css('display', 'block');
                        $('.paperConsumptionCN').css('display', 'block');
                        // $('#paperConsumptionTS').addClass('active');

                        $('#waternav').css('display', 'none');
                        $('#energyNav').css('display', 'none');
                }, 500);

                </script>
        @endif
    @endif --}}

    </html>
@else
    <script type="text/javascript">
        window.location = "/";
    </script>
@endauth
