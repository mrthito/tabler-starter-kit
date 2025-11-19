<div class="row g-0 flex-fill">
    <div class="col-12 col-lg-6 col-xl-4 border-top-wide border-primary d-flex flex-column justify-content-center">
        <div class="container container-tight my-5 px-lg-5">
            <div class="text-center mb-4">
                <a href="{{ route('admin.dashboard') }}" aria-label="{{ config('app.name') }}"
                    class="navbar-brand navbar-brand-autodark">
                    <x-application-logo class="navbar-brand-image" />
                </a>
            </div>
            {{ $slot }}
        </div>
    </div>
    <div class="col-12 col-lg-6 col-xl-8 d-none d-lg-block">
        <!-- Photo -->
        <div class="bg-cover h-100 min-vh-100"
            style="background-image: url({{ asset('assets/images/cover.jpg') }})">
        </div>
    </div>
</div>
