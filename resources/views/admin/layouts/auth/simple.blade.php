<div class="container container-tight py-4">
    <div class="text-center mb-4">
        <a href="{{ route('admin.dashboard') }}" aria-label="{{ config('app.name') }}"
            class="navbar-brand navbar-brand-autodark">
            <x-application-logo class="navbar-brand-image" />
        </a>
    </div>

    {{ $slot }}
</div>
