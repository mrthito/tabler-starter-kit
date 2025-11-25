<x-app-layout :page="__('Edit Post')" layout="admin">

    <x-slot name="pretitle">{{ __('Posts') }}</x-slot>
    <x-slot name="subtitle">{{ __('Edit Post') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.posts.update', $post) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Basic Information') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Title') }}</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title', $post->title) }}"
                                        placeholder="{{ __('Enter post title') }}" required>
                                    <x-common.error name="title" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        name="slug" value="{{ old('slug', $post->slug) }}"
                                        placeholder="{{ __('Leave blank to auto-generate from title') }}">
                                    <small
                                        class="text-muted">{{ __('Leave blank to auto-generate from title') }}</small>
                                    <x-common.error name="slug" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Content') }}</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="15"
                                        placeholder="{{ __('Enter post content') }}" required>{{ old('content', $post->content) }}</textarea>
                                    <x-common.error name="content" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Excerpt') }}</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" name="excerpt" rows="3"
                                        placeholder="{{ __('Enter post excerpt (optional)') }}">{{ old('excerpt', $post->excerpt) }}</textarea>
                                    <small
                                        class="text-muted">{{ __('Brief description of the post (max 500 characters)') }}</small>
                                    <x-common.error name="excerpt" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Post Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Post Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Post Type') }}</label>
                                    <select class="form-select @error('post_type') is-invalid @enderror"
                                        name="post_type">
                                        <option value="blog"
                                            {{ old('post_type', $post->post_type) == 'blog' ? 'selected' : '' }}>
                                            {{ __('Blog') }}</option>
                                        <option value="page"
                                            {{ old('post_type', $post->post_type) == 'page' ? 'selected' : '' }}>
                                            {{ __('Page') }}</option>
                                        <option value="news"
                                            {{ old('post_type', $post->post_type) == 'news' ? 'selected' : '' }}>
                                            {{ __('News') }}</option>
                                    </select>
                                    <x-common.error name="post_type" />
                                </div>

                                <input type="hidden" name="status" value="0">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" value="1"
                                            {{ old('status', $post->status) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ __('Published') }}</span>
                                    </label>
                                </div>

                                <input type="hidden" name="is_featured" value="0">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_featured"
                                            value="1"
                                            {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ __('Featured') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Featured Image') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Image URL') }}</label>
                                    <input type="text" class="form-control @error('image') is-invalid @enderror"
                                        name="image" value="{{ old('image', $post->image) }}"
                                        placeholder="{{ __('Enter image URL') }}">
                                    <small class="text-muted">{{ __('Enter the URL of the featured image') }}</small>
                                    <x-common.error name="image" />
                                </div>
                                @if (old('image', $post->image))
                                    <div class="mt-2">
                                        <img src="{{ old('image', $post->image) }}" alt="Preview"
                                            class="img-thumbnail" style="max-width: 100%; height: auto;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Categories') }}</h3>
                            </div>
                            <div class="card-body">
                                @if ($categories->count() > 0)
                                    <div class="categories-container">
                                        @php
                                            $selectedCategories = old(
                                                'categories',
                                                $post->postCategories->pluck('id')->toArray(),
                                            );
                                        @endphp
                                        @foreach ($categories as $category)
                                            <div class="mb-2">
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="categories[]" value="{{ $category->id }}"
                                                        {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                                    <span class="form-check-label">{{ $category->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">{{ __('No categories available') }}</p>
                                @endif
                                <x-common.error name="categories" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Update Post') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-generate slug from title
                const titleInput = document.querySelector('input[name="title"]');
                const slugInput = document.querySelector('input[name="slug"]');
                let slugManuallyEdited = false;

                if (titleInput && slugInput) {
                    // Check if slug was manually edited
                    const originalSlug = slugInput.value;
                    slugInput.addEventListener('input', function() {
                        if (this.value !== originalSlug) {
                            slugManuallyEdited = true;
                        }
                    });

                    titleInput.addEventListener('input', function() {
                        if (!slugManuallyEdited) {
                            slugInput.value = this.value.toLowerCase()
                                .replace(/[^a-z0-9]+/g, '-')
                                .replace(/^-+|-+$/g, '');
                        }
                    });
                }

                // Image preview
                const imageInput = document.querySelector('input[name="image"]');
                if (imageInput) {
                    imageInput.addEventListener('input', function() {
                        const preview = document.querySelector('.img-thumbnail');
                        if (this.value) {
                            if (preview) {
                                preview.src = this.value;
                                preview.style.display = 'block';
                            } else {
                                const img = document.createElement('img');
                                img.src = this.value;
                                img.className = 'img-thumbnail';
                                img.style.cssText = 'max-width: 100%; height: auto;';
                                imageInput.parentElement.nextElementSibling.appendChild(img);
                            }
                        } else if (preview) {
                            preview.style.display = 'none';
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
