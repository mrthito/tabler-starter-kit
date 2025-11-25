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

                        <!-- SEO Section -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('SEO Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Focus Keyword') }}</label>
                                    <input type="text"
                                        class="form-control @error('focus_keyword') is-invalid @enderror"
                                        name="focus_keyword" value="{{ old('focus_keyword', $post->focus_keyword) }}"
                                        placeholder="{{ __('Enter focus keyword') }}" id="focus_keyword">
                                    <small class="text-muted">{{ __('Main keyword for this post') }}</small>
                                    <x-common.error name="focus_keyword" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Meta Title') }}</label>
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                        name="meta_title" value="{{ old('meta_title', $post->meta_title) }}"
                                        placeholder="{{ __('Enter meta title (recommended: 50-60 characters)') }}"
                                        id="meta_title" maxlength="60">
                                    <small class="text-muted">
                                        <span id="meta_title_count">0</span>/60 {{ __('characters') }}
                                    </small>
                                    <x-common.error name="meta_title" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Meta Description') }}</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" rows="3"
                                        placeholder="{{ __('Enter meta description (recommended: 150-160 characters)') }}" id="meta_description"
                                        maxlength="160">{{ old('meta_description', $post->meta_description) }}</textarea>
                                    <small class="text-muted">
                                        <span id="meta_description_count">0</span>/160 {{ __('characters') }}
                                    </small>
                                    <x-common.error name="meta_description" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Meta Keywords') }}</label>
                                    <input type="text"
                                        class="form-control @error('meta_keywords') is-invalid @enderror"
                                        name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}"
                                        placeholder="{{ __('Enter keywords separated by commas') }}">
                                    <small class="text-muted">{{ __('Comma-separated keywords') }}</small>
                                    <x-common.error name="meta_keywords" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Canonical URL') }}</label>
                                    <input type="text"
                                        class="form-control @error('canonical_url') is-invalid @enderror"
                                        name="canonical_url" value="{{ old('canonical_url', $post->canonical_url) }}"
                                        placeholder="{{ __('Enter canonical URL (optional)') }}">
                                    <small class="text-muted">{{ __('Leave blank to use default post URL') }}</small>
                                    <x-common.error name="canonical_url" />
                                </div>

                                <div class="accordion" id="seoAccordion">
                                    <!-- Open Graph -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#ogCollapse">
                                                {{ __('Open Graph (Facebook)') }}
                                            </button>
                                        </h2>
                                        <div id="ogCollapse" class="accordion-collapse collapse"
                                            data-bs-parent="#seoAccordion">
                                            <div class="accordion-body">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('OG Title') }}</label>
                                                    <input type="text"
                                                        class="form-control @error('og_title') is-invalid @enderror"
                                                        name="og_title"
                                                        value="{{ old('og_title', $post->og_title) }}"
                                                        placeholder="{{ __('Leave blank to use meta title') }}"
                                                        maxlength="60">
                                                    <x-common.error name="og_title" />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('OG Description') }}</label>
                                                    <textarea class="form-control @error('og_description') is-invalid @enderror" name="og_description" rows="2"
                                                        placeholder="{{ __('Leave blank to use meta description') }}" maxlength="200">{{ old('og_description', $post->og_description) }}</textarea>
                                                    <x-common.error name="og_description" />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('OG Image URL') }}</label>
                                                    <input type="text"
                                                        class="form-control @error('og_image') is-invalid @enderror"
                                                        name="og_image"
                                                        value="{{ old('og_image', $post->og_image) }}"
                                                        placeholder="{{ __('Leave blank to use featured image') }}">
                                                    <x-common.error name="og_image" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Twitter Card -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#twitterCollapse">
                                                {{ __('Twitter Card') }}
                                            </button>
                                        </h2>
                                        <div id="twitterCollapse" class="accordion-collapse collapse"
                                            data-bs-parent="#seoAccordion">
                                            <div class="accordion-body">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Twitter Title') }}</label>
                                                    <input type="text"
                                                        class="form-control @error('twitter_title') is-invalid @enderror"
                                                        name="twitter_title"
                                                        value="{{ old('twitter_title', $post->twitter_title) }}"
                                                        placeholder="{{ __('Leave blank to use meta title') }}"
                                                        maxlength="60">
                                                    <x-common.error name="twitter_title" />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Twitter Description') }}</label>
                                                    <textarea class="form-control @error('twitter_description') is-invalid @enderror" name="twitter_description"
                                                        rows="2" placeholder="{{ __('Leave blank to use meta description') }}" maxlength="200">{{ old('twitter_description', $post->twitter_description) }}</textarea>
                                                    <x-common.error name="twitter_description" />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Twitter Image URL') }}</label>
                                                    <input type="text"
                                                        class="form-control @error('twitter_image') is-invalid @enderror"
                                                        name="twitter_image"
                                                        value="{{ old('twitter_image', $post->twitter_image) }}"
                                                        placeholder="{{ __('Leave blank to use featured image') }}">
                                                    <x-common.error name="twitter_image" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Preview -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Search Engine Preview') }}</h3>
                            </div>
                            <div class="card-body">
                                <div id="seo-preview" class="seo-preview">
                                    <div class="mb-2">
                                        <div class="text-primary" style="font-size: 14px; line-height: 1.3;"
                                            id="preview_url">
                                            {{ config('app.url') }}/posts/{{ old('slug', $post->slug) }}
                                        </div>
                                        <div class="fw-bold text-primary"
                                            style="font-size: 20px; line-height: 1.3; margin-top: 2px;"
                                            id="preview_title">
                                            {{ old('meta_title', $post->meta_title) ?: old('title', $post->title) }}
                                        </div>
                                        <div class="text-muted"
                                            style="font-size: 14px; line-height: 1.3; margin-top: 2px;"
                                            id="preview_description">
                                            {{ old('meta_description', $post->meta_description) ?: old('excerpt', $post->excerpt) ?: __('Enter meta description to see preview') }}
                                        </div>
                                    </div>
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
                                        <option value="news"
                                            {{ old('post_type', $post->post_type) == 'news' ? 'selected' : '' }}>
                                            {{ __('News') }}</option>
                                    </select>
                                    <x-common.error name="post_type" />
                                </div>

                                <input type="hidden" name="status" value="0">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status"
                                            value="1" {{ old('status', $post->status) ? 'checked' : '' }}>
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

                // SEO Character Counters
                const metaTitleInput = document.getElementById('meta_title');
                const metaDescriptionInput = document.getElementById('meta_description');
                const metaTitleCount = document.getElementById('meta_title_count');
                const metaDescriptionCount = document.getElementById('meta_description_count');

                if (metaTitleInput && metaTitleCount) {
                    metaTitleInput.addEventListener('input', function() {
                        metaTitleCount.textContent = this.value.length;
                        updateSEOPreview();
                    });
                    metaTitleCount.textContent = metaTitleInput.value.length;
                }

                if (metaDescriptionInput && metaDescriptionCount) {
                    metaDescriptionInput.addEventListener('input', function() {
                        metaDescriptionCount.textContent = this.value.length;
                        updateSEOPreview();
                    });
                    metaDescriptionCount.textContent = metaDescriptionInput.value.length;
                }

                // SEO Preview Update
                function updateSEOPreview() {
                    const title = metaTitleInput?.value || document.querySelector('input[name="title"]')?.value ||
                        '{{ __('Enter meta title to see preview') }}';
                    const description = metaDescriptionInput?.value || document.querySelector(
                        'textarea[name="excerpt"]')?.value || '{{ __('Enter meta description to see preview') }}';
                    const slug = document.querySelector('input[name="slug"]')?.value || '{{ $post->slug }}';

                    const previewTitle = document.getElementById('preview_title');
                    const previewDescription = document.getElementById('preview_description');
                    const previewUrl = document.getElementById('preview_url');

                    if (previewTitle) {
                        previewTitle.textContent = title || '{{ __('Enter meta title to see preview') }}';
                    }
                    if (previewDescription) {
                        previewDescription.textContent = description ||
                            '{{ __('Enter meta description to see preview') }}';
                    }
                    if (previewUrl && slug) {
                        previewUrl.textContent = '{{ config('app.url') }}/posts/' + slug;
                    }
                }

                // Update preview when title or excerpt changes
                const titleInput = document.querySelector('input[name="title"]');
                const excerptInput = document.querySelector('textarea[name="excerpt"]');
                const slugInput = document.querySelector('input[name="slug"]');

                if (titleInput) {
                    titleInput.addEventListener('input', function() {
                        if (!metaTitleInput?.value) {
                            updateSEOPreview();
                        }
                    });
                }

                if (excerptInput) {
                    excerptInput.addEventListener('input', function() {
                        if (!metaDescriptionInput?.value) {
                            updateSEOPreview();
                        }
                    });
                }

                if (slugInput) {
                    slugInput.addEventListener('input', updateSEOPreview);
                }

                // Initial preview update
                updateSEOPreview();
            });
        </script>
    @endpush
</x-app-layout>
