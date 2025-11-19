<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ route('admin.dashboard') }}" aria-label="Tabler">
                <x-application-logo class="navbar-brand-image" />
            </a>
        </div>
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item d-none d-md-flex me-3">
                <div class="btn-list">
                    <a href="https://github.com/tabler/tabler" class="btn btn-5" target="_blank" rel="noreferrer">
                        <i class="ti ti-brand-github icon icon-2"></i>
                        {{ __('Source code') }}
                    </a>
                    <a href="https://github.com/sponsors/codecalm" class="btn btn-6" target="_blank" rel="noreferrer">
                        <i class="ti ti-heart icon icon-2 text-pink"></i>
                        {{ __('Sponsor') }}
                    </a>
                </div>
            </div>
            
            @include('admin.layouts.header-items')

        </div>
    </div>
</header>
