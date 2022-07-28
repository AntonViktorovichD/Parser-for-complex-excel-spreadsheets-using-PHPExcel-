<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME', 'Permissions Manager') }}</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/coreui.min.css" rel="stylesheet" />
    <link href="/css/font-awesome.css" rel="stylesheet" />
    <link href="/css/all.css" rel="stylesheet" />
    <link href="/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="/css/font-awesome.css" rel="stylesheet" />
    <link href="/css/select2.min.css" rel="stylesheet" />
    <link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="/css/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles')
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
<div class="app flex-row align-items-center">
    <div class="container">
        @yield("content")
    </div>
</div>
@yield('scripts')
</body>

</html>
