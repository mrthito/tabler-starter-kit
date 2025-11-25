<div class="page">
    @include('admin.layouts.app.condensed.navigation')
    <div class="page-wrapper">
        <div class="page-header d-print-none" aria-label="Page header">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        @if (isset($pretitle))
                            <div class="page-pretitle">{{ $pretitle }}</div>
                        @endif
                        @if (isset($subtitle))
                            <h2 class="page-title">{{ $subtitle }}</h2>
                        @endif
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            @isset($actions)
                                {{ $actions }}
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                {{ $slot }}
            </div>
        </div>
        @include('admin.layouts.app.partials.footer')
    </div>
</div>
