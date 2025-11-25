@props([
    'title' => null,
    'description' => null,
    'export' => [],
    'action' => null,
])

@php
    $queryParams = request()->except('search', 'page');
    $params = [];
    foreach ($queryParams as $key => $value) {
        $params[$key] = $value;
    }
@endphp

<div class="card">
    <div class="card-table">
        <div class="card-header">
            <div class="row w-full">
                <div class="col">
                    <h3 class="card-title mb-0">{{ $title }}</h3>
                    <p class="text-secondary m-0">{{ $description }}</p>
                </div>
                <div class="col-md-auto col-sm-12">
                    <div class="ms-auto d-flex flex-wrap btn-list">
                        <form action="{{ url()->current() }}" method="get" class="input-group input-group-flat w-auto">
                            @foreach ($params as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <span class="input-group-text">
                                <i class="ti ti-search icon icon-1"></i>
                            </span>
                            <input id="advanced-table-search" type="text" class="form-control" autocomplete="off"
                                placeholder="{{ __('Search') }}" name="search" value="{{ request('search') }}">
                            <span class="input-group-text">
                                <kbd>ctrl + K</kbd>
                            </span>
                        </form>
                        <a href="#" class="btn btn-icon" aria-label="Button">
                            <i class="ti ti-dots icon icon-1"></i>
                        </a>
                        <div class="dropdown">
                            <a href="#" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                {{ __('Download') }}
                            </a>
                            <div class="dropdown-menu">
                                @if (isset($export['pdf']))
                                    <a class="dropdown-item" href="{{ $export['pdf'] }}">
                                        <i class="ti ti-file-type-pdf icon icon-1 me-2"></i>
                                        {{ __('PDF') }}
                                    </a>
                                @endif
                                @if (isset($export['xls']))
                                    <a class="dropdown-item" href="{{ $export['xls'] }}">
                                        <i class="ti ti-file-type-xls icon icon-1 me-2"></i>
                                        {{ __('Excel') }}
                                    </a>
                                @endif
                                @if (isset($export['csv']))
                                    <a class="dropdown-item" href="{{ $export['csv'] }}">
                                        <i class="ti ti-file-type-csv icon icon-1 me-2"></i>
                                        {{ __('CSV') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger delete-selected-btn" data-bs-toggle="modal"
                            data-bs-target="#delete-selected-modal">
                            <i class="ti ti-trash icon icon-1 me-2"></i>
                            {{ __('Delete Selected') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="advanced-table">
            <div class="table-responsive">
                {{ $slot }}
            </div>
            @isset($footer)
                {{ $footer }}
            @endisset
        </div>
    </div>
</div>



<div class="modal modal-blur fade" id="delete-selected-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <i class="ti ti-trash icon mb-2 text-danger icon-lg"></i>
                <h3>{{ __('Are you sure?') }}</h3>
                <div class="text-secondary">
                    {{ __('Do you really want to delete the selected items?') }}
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-3 w-100"
                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        </div>
                        <div class="col">
                            <form method="post" action="{{ $action }}">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="rows" value="" id="selected-rows">
                                <x-common.btn type="submit" class="btn btn-danger btn-4 w-100"
                                    data-bs-dismiss="modal">{{ __('Delete') }}</x-common.btn>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
