<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link name="favicon" type="image/x-icon" href="{{asset('img/favicon.png')}}" rel="shortcut icon" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!--Font Awesome [ OPTIONAL ]-->
    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">

    <!--active-shop Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/active-shop.min.css')}}" rel="stylesheet">

    <!--active-shop Premium Icon [ DEMONSTRATION ]-->
    <link href="{{ asset('css/demo/active-shop-demo-icons.min.css')}}" rel="stylesheet">

    <!--Demo [ DEMONSTRATION ]-->
    <link href="{{ asset('css/demo/active-shop-demo.min.css') }}" rel="stylesheet">

    <!--Theme [ DEMONSTRATION ]-->
    <link href="{{ asset('css/themes/type-c/theme-navy.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-rtl.css') }}" rel="stylesheet">
    <style>
        body{
            background: #fff;
        }
        @-webkit-keyframes swinging {
            0% {
                -webkit-transform: rotate(10deg);
            }
            50% {
                -webkit-transform: rotate(-5deg);
            }
            100% {
                -webkit-transform: rotate(10deg);
            }
            }
            @keyframes swinging {
            0% {
                transform: rotate(10deg);
            }
            50% {
                transform: rotate(-5deg);
            }
            100% {
                transform: rotate(10deg);
            }
            }
            @-webkit-keyframes sleeping {
            0% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
            }
            @keyframes sleeping {
            0% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
            }
            #swinger {
            -webkit-transform-origin: top center;
            transform-origin: top center;
            transform-box: fill-box;
            -webkit-animation: swinging 3.5s ease-in-out forwards infinite;
            animation: swinging 3.5s ease-in-out forwards infinite;
            }

            #zone {
            opacity: 0;
            -webkit-animation: sleeping 3.5s ease-in-out forwards infinite;
            animation: sleeping 3.5s ease-in-out forwards infinite;
            }

            #ztwo {
            opacity: 0;
            -webkit-animation: sleeping 3.5s ease-in-out forwards infinite;
            animation: sleeping 3.5s ease-in-out forwards infinite;
            animation-delay: 0.4s;
            -website-animation-delay: 0.4s;
            }

            #zthree {
            opacity: 0;
            -webkit-animation: sleeping 3.5s ease-in-out forwards infinite;
            animation: sleeping 3.5s ease-in-out forwards infinite;
            animation-delay: 0.7s;
            -website-animation-delay: 0.7s;
            }

            #zfour {
            opacity: 0;
            -webkit-animation: sleeping 3.5s ease-in-out forwards infinite;
            animation: sleeping 3.5s ease-in-out forwards infinite;
            animation-delay: 1s;
            -website-animation-delay: 1s;
            }

            #zfive {
            opacity: 0;
            -webkit-animation: sleeping 3.5s ease-in-out forwards infinite;
            animation: sleeping 3.5s ease-in-out forwards infinite;
            animation-delay: 1.3s;
            -website-animation-delay: 1.3s;
            }

            .container {
            text-align: center;
            }

            svg {
            max-width: 1000px;
            }

    </style>

    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <script src=" {{asset('js/jquery.min.js') }}"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>


    <!--active-shop [ RECOMMENDED ]-->
    <script src="{{ asset('js/active-shop.min.js') }}"></script>

    <!--Alerts [ SAMPLE ]-->
    <script src="{{ asset('js/demo/ui-alerts.js') }}"></script>

    <!--Switchery [ OPTIONAL ]-->
    <script src="{{ asset('plugins/switchery/switchery.min.js')}}"></script>

    <!--DataTables [ OPTIONAL ]-->
    <script src="{{ asset('plugins/datatables/media/js/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('plugins/datatables/media/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>

    <!--DataTables Sample [ SAMPLE ]-->
    <script src="{{ asset('js/demo/tables-datatables.js')}}"></script>

    <!--Select2 [ OPTIONAL ]-->
    <script src="{{ asset('plugins/select2/js/select2.min.js')}}"></script>

    <!--Summernote [ OPTIONAL ]-->
    <script src="{{ asset('js/jodit.min.js') }}"></script>

    <!--Bootstrap Tags Input [ OPTIONAL ]-->
    <script src="{{ asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>

    <!--Bootstrap Validator [ OPTIONAL ]-->
    <script src="{{ asset('plugins/bootstrap-validator/bootstrapValidator.min.js') }}"></script>

    <!--Bootstrap Wizard [ OPTIONAL ]-->
    <script src="{{ asset('plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>

    <!--Bootstrap Datepicker [ OPTIONAL ]-->
    <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <!--Form Component [ SAMPLE ]-->
    <script src="{{asset('js/demo/form-wizard.js')}}"></script>

    <!--Spectrum JavaScript [ REQUIRED ]-->
    <script src="{{ asset('js/spectrum.js')}}"></script>

    <!--Spartan Image JavaScript [ REQUIRED ]-->
    <script src="{{ asset('js/spartan-multi-image-picker-min.js') }}"></script>

    <!--Custom JavaScript [ REQUIRED ]-->
    <script src="{{ asset('js/custom.js')}}"></script>

</head>
<body>
    @foreach (session('flash_notification', collect())->toArray() as $message)
        <script type="text/javascript">
            $(document).on('nifty.ready', function() {
                showAlert('{{ $message['level'] }}', '{{ $message['message'] }}');
            });
        </script>
    @endforeach
    <div id="container" class="">
        <div class="cls-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('script')

</body>
</html>
