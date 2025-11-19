<header class="navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ route('admin.dashboard') }}" aria-label="{{ config('app.name') }}">
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
        </div>
    </div>
</header>
