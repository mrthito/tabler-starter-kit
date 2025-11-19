<header class="navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
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
