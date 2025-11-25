<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\StorageSettingService;

class UpdateStorageSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $provider = $this->input('provider', 'local');
        $storageSettingService = app(StorageSettingService::class);
        $configFields = $storageSettingService->getProviderConfigFields($provider);

        $rules = [
            'provider' => ['required', 'string', 'in:local,s3,wasabi,digitalocean,backblaze'],
        ];

        // Add validation rules for config fields
        foreach ($configFields as $key => $field) {
            $fieldType = $field['type'] ?? 'text';
            if ($fieldType === 'checkbox') {
                $rules["config.{$key}"] = ['nullable', 'boolean'];
            } elseif ($field['required'] ?? false) {
                $rules["config.{$key}"] = ['required', 'string'];
            } else {
                $rules["config.{$key}"] = ['nullable', 'string'];
            }
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        // Convert config array to proper format
        if ($this->has('config') && is_array($this->config)) {
            $this->merge([
                'config' => array_filter($this->config, function ($value) {
                    return $value !== null && $value !== '';
                }),
            ]);
        }
    }
}
