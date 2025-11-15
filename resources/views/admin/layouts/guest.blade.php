<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title }}</title>
    @include('admin.layouts.partials.header')
</head>

<body>
    <div class="page page-center">
        @include('admin.layouts.auth.simple') {{-- illustration, simple, cover --}}
    </div>

    @include('admin.layouts.partials.footer')
</body>

</html>
