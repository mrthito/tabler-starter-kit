<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav flex-row order-md-last">
            @include('admin.layouts.header-items')
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav">
                @menu('admin-right', 'admin.layouts.sidemenu')
            </ul>
        </div>
    </div>
</header>
