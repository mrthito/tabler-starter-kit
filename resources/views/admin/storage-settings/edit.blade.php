<x-app-layout :page="__('Storage Settings')" layout="admin">

    <x-slot name="pretitle">{{ __('Storage Settings') }}</x-slot>
    <x-slot name="subtitle">{{ __('Configure Default Storage Provider') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">{{ __('Back to Dashboard') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.storage-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Provider Selection -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Default Storage Provider') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Provider') }}</label>
                                    <select class="form-select @error('provider') is-invalid @enderror" name="provider"
                                        id="storage_provider" required>
                                        <option value="local"
                                            {{ old('provider', $storageSetting->provider) == 'local' ? 'selected' : '' }}>
                                            {{ __('Local Storage') }}</option>
                                        <option value="s3"
                                            {{ old('provider', $storageSetting->provider) == 's3' ? 'selected' : '' }}>
                                            {{ __('Amazon S3') }}</option>
                                        <option value="wasabi"
                                            {{ old('provider', $storageSetting->provider) == 'wasabi' ? 'selected' : '' }}>
                                            {{ __('Wasabi') }}</option>
                                        <option value="digitalocean"
                                            {{ old('provider', $storageSetting->provider) == 'digitalocean' ? 'selected' : '' }}>
                                            {{ __('DigitalOcean Spaces') }}</option>
                                        <option value="backblaze"
                                            {{ old('provider', $storageSetting->provider) == 'backblaze' ? 'selected' : '' }}>
                                            {{ __('Backblaze B2') }}</option>
                                    </select>
                                    <x-common.error name="provider" />
                                </div>

                                <!-- Dynamic Configuration Fields -->
                                <div id="config_fields_container">
                                    <!-- Fields will be dynamically generated here -->
                                </div>
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
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Update Storage Setting') }}
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
                const providerSelect = document.getElementById('storage_provider');
                const configContainer = document.getElementById('config_fields_container');

                // Provider configuration fields
                const providerFields = {
                    local: [],
                    s3: [{
                            key: 'key',
                            label: 'Access Key ID',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'secret',
                            label: 'Secret Access Key',
                            type: 'password',
                            required: true
                        },
                        {
                            key: 'region',
                            label: 'Region',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'bucket',
                            label: 'Bucket',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'endpoint',
                            label: 'Endpoint (Optional)',
                            type: 'text',
                            required: false
                        },
                        {
                            key: 'url',
                            label: 'URL (Optional)',
                            type: 'text',
                            required: false
                        },
                        {
                            key: 'use_path_style_endpoint',
                            label: 'Use Path Style Endpoint',
                            type: 'checkbox',
                            required: false
                        },
                    ],
                    wasabi: [{
                            key: 'key',
                            label: 'Access Key ID',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'secret',
                            label: 'Secret Access Key',
                            type: 'password',
                            required: true
                        },
                        {
                            key: 'region',
                            label: 'Region',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'bucket',
                            label: 'Bucket',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'endpoint',
                            label: 'Endpoint',
                            type: 'text',
                            required: true
                        },
                    ],
                    digitalocean: [{
                            key: 'key',
                            label: 'Access Key ID',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'secret',
                            label: 'Secret Access Key',
                            type: 'password',
                            required: true
                        },
                        {
                            key: 'region',
                            label: 'Region',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'bucket',
                            label: 'Bucket',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'endpoint',
                            label: 'Endpoint',
                            type: 'text',
                            required: true
                        },
                    ],
                    backblaze: [{
                            key: 'key',
                            label: 'Application Key ID',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'secret',
                            label: 'Application Key',
                            type: 'password',
                            required: true
                        },
                        {
                            key: 'bucket',
                            label: 'Bucket',
                            type: 'text',
                            required: true
                        },
                        {
                            key: 'endpoint',
                            label: 'Endpoint',
                            type: 'text',
                            required: true
                        },
                    ],
                };

                function renderConfigFields(provider) {
                    const fields = providerFields[provider] || [];
                    let html = '';

                    if (fields.length > 0) {
                        html +=
                            '<div class="mb-3"><h4 class="card-title">{{ __('Provider Configuration') }}</h4></div>';
                        fields.forEach(field => {
                            const fieldName = `config[${field.key}]`;
                            const existingConfig = @json($storageSetting->config ?? []);
                            const oldConfig = @json(old('config', []));
                            const value = oldConfig[field.key] || existingConfig[field.key] || '';

                            if (field.type === 'checkbox') {
                                html += `
                                    <div class="mb-3">
                                        <input type="hidden" name="${fieldName}" value="0">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="${fieldName}" value="1" ${value ? 'checked' : ''}>
                                            <span class="form-check-label">${field.label}${field.required ? ' <span class="text-danger">*</span>' : ''}</span>
                                        </label>
                                    </div>
                                `;
                            } else {
                                html += `
                                    <div class="mb-3">
                                        <label class="form-label">${field.label}${field.required ? ' <span class="text-danger">*</span>' : ''}</label>
                                        <input type="${field.type}" 
                                            class="form-control" 
                                            name="${fieldName}" 
                                            value="${value}"
                                            ${field.required ? 'required' : ''}
                                            ${field.type === 'password' ? 'autocomplete="new-password"' : ''}>
                                    </div>
                                `;
                            }
                        });
                    } else {
                        html +=
                            '<div class="alert alert-info">{{ __('No configuration required for local storage.') }}</div>';
                    }

                    configContainer.innerHTML = html;
                }

                if (providerSelect) {
                    providerSelect.addEventListener('change', function() {
                        renderConfigFields(this.value);
                    });
                    // Initialize on page load with existing values
                    const currentProvider = providerSelect.value;
                    renderConfigFields(currentProvider);
                }
            });
        </script>
    @endpush
</x-app-layout>
