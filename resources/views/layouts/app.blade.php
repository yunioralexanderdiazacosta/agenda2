<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <!--<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">-->

    <!-- Styles -->
    <!--<link rel="stylesheet" href="mix('css/app.css')">-->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    @livewireStyles
    @stack('styles-calendar')
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="sidenav-toggled">
    @livewire('navigation-menu')
    <div id="content" class="mt-3">
        <div class="container-fluid mt-4 mb-3">
            <div class="row mt-5">
                <div class="col-lg-12">
                    <!-- Page Content -->
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    @stack('modals')
    @livewireScripts
    <script type="text/javascript">
        window.livewire.on('show-form', () => {
            $('#show-form').modal('show');
        });
        window.livewire.on('hide-form', () => {
            $('#show-form').modal('hide');
        });
        window.livewire.on('show-p-create', () => {
            $('#p-create').modal('show');
        });
        window.livewire.on('hide-p-create', () => {
            $('#p-create').modal('hide');
        });
        window.livewire.on('show-p-view', () => {
            $('#p-view').modal('show');
        });
        window.livewire.on('hide-p-create', () => {
            $('#p-view').modal('hide');
        });
    </script>
    <x-livewire-alert::scripts />
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('popper/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('js/toggle-menu.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/lib/main.min.css') }}">
    <script src="{{ asset('plugins/fullcalendar/lib/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/lib/locales/es.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>  
    <script>
        feather.replace({ 'aria-hidden': 'true' })
    </script>
    @stack('scripts')
</body>
</html>