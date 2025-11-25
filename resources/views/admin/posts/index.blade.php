<x-app-layout :page="__('Posts')" layout="admin">

    <x-slot name="pretitle">{{ __('Posts') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Posts') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">{{ __('Create Post') }}</a>
    </x-slot>

    <div class="row row-cards">
        <div class="col-12">
            <x-table.card :title="__('Posts')" :description="__('Manage all posts here')" :action="route('admin.posts.destroy', 'bulk')">
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
                                    data-sort="sort-type">{{ __('Type') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-categories">{{ __('Categories') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-featured">{{ __('Featured') }}</button>
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
                        @forelse ($posts as $post)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                        name="row[]" type="checkbox" value="{{ $post->id }}">
                                </td>
                                <td class="sort-title">
                                    <div class="d-flex align-items-center">
                                        @if ($post->image)
                                            <img src="{{ $post->image }}" alt="{{ $post->title }}"
                                                class="avatar avatar-sm me-2" style="object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $post->title }}</div>
                                            <small
                                                class="text-muted">{{ \Illuminate\Support\Str::limit($post->excerpt, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="sort-type">
                                    <span class="badge bg-info-lt">
                                        {{ ucfirst($post->post_type ?? 'blog') }}
                                    </span>
                                </td>
                                <td class="sort-categories">
                                    @if ($post->postCategories->count() > 0)
                                        @foreach ($post->postCategories->take(2) as $category)
                                            <span class="badge bg-secondary-lt me-1">{{ $category->name }}</span>
                                        @endforeach
                                        @if ($post->postCategories->count() > 2)
                                            <span
                                                class="badge bg-secondary-lt">+{{ $post->postCategories->count() - 2 }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">{{ __('No categories') }}</span>
                                    @endif
                                </td>
                                <td class="sort-featured">
                                    @if ($post->is_featured)
                                        <span class="badge bg-warning-lt">
                                            <i class="ti ti-star icon icon-1"></i>
                                            {{ __('Featured') }}
                                        </span>
                                    @else
                                        <span class="text-muted">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="sort-status">
                                    @if ($post->status)
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
                                    <small class="text-muted">{{ $post->created_at->format('M d, Y') }}</small>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.posts.edit', $post->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="ti ti-edit icon icon-1"></i>
                                        {{ __('Edit') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('No data found') }}</td>
                            </tr>
                        @endforelse
                    </x-slot>
                </x-table.table>
                <x-slot name="footer">
                    <div class="card-footer d-flex align-items-center">
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                <span id="page-count" class="me-1">{{ $posts->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $posts->withQueryString()->links('pagination::bs5') }}
                    </div>
                </x-slot>
            </x-table.card>
        </div>
    </div>
</x-app-layout>
