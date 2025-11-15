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
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ route('admin.dashboard') }}" aria-label="{{ config('app.name') }}"
                    class="navbar-brand navbar-brand-autodark">
                    <x-application-logo class="navbar-brand-image" />
                </a>
            </div>

            {{ $slot }}

        </div>
    </div>

    @include('admin.layouts.partials.footer')
</body>

</html>
