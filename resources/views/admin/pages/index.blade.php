<x-app-layout :page="__('Pages')" layout="admin">

    <x-slot name="pretitle">{{ __('Pages') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Pages') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">{{ __('Create Page') }}</a>
    </x-slot>

    <div class="row row-cards">
        <div class="col-12">
            <x-table.card :title="__('Pages')" :description="__('Manage all pages here')" :action="route('admin.pages.destroy', 'bulk')">
                <x-table.table>
                    <x-slot name="thead">
                        <tr>
                            <th class="w-1"></th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-title">{{ __('Title') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-slug">{{ __('Slug') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-status">{{ __('Status') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-date">{{ __('Created') }}</button>
                            </th>
                            <th width="10px"></th>
                        </tr>
                    </x-slot>
                    <x-slot name="tbody">
                        @forelse ($pages as $page)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                        name="row[]" type="checkbox" value="{{ $page->id }}">
                                </td>
                                <td class="sort-title">
                                    <div class="d-flex align-items-center">
                                        @if ($page->image)
                                            <img src="{{ $page->image }}" alt="{{ $page->title }}"
                                                class="avatar avatar-sm me-2" style="object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $page->title }}</div>
                                            <small
                                                class="text-muted">{{ \Illuminate\Support\Str::limit($page->excerpt, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="sort-slug">
                                    <code class="text-muted">{{ $page->slug }}</code>
                                </td>
                                <td class="sort-status">
                                    @if ($page->status)
                                        <span class="badge bg-success-lt">
                                            <i class="ti ti-check icon icon-1"></i>
                                            {{ __('Published') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger-lt">
                                            <i class="ti ti-x icon icon-1"></i>
                                            {{ __('Draft') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="sort-date">
                                    <small class="text-muted">{{ $page->created_at->format('M d, Y') }}</small>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.pages.edit', $page->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="ti ti-edit icon icon-1"></i>
                                        {{ __('Edit') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __('No data found') }}</td>
                            </tr>
                        @endforelse
                    </x-slot>
                </x-table.table>
                <x-slot name="footer">
                    <div class="card-footer d-flex align-items-center">
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                <span id="page-count" class="me-1">{{ $pages->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $pages->withQueryString()->links('pagination::bs5') }}
                    </div>
                </x-slot>
            </x-table.card>
        </div>
    </div>
</x-app-layout>
