<header class="navbar navbar-expand-md navbar-overlap d-print-none" data-bs-theme="dark">
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
        <div class="row flex-column flex-md-row flex-fill align-items-center">
            <div class="col">
                <ul class="navbar-nav">
                    @menu('admin', 'admin.layouts.sidemenu')
                </ul>
            </div>
            <div class="col col-md-auto">
                <ul class="navbar-nav">
                    @menu('admin-right', 'admin.layouts.sidemenu')
                </ul>
            </div>
        </div>
    </div>
</header>
