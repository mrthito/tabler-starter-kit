<?php

namespace App\Services;

use App\Models\StorageSetting;

class StorageSettingService
{
    public function __construct(protected StorageSetting $storageSetting) {}

    /**
     * Get the storage setting (singleton - only one exists)
     */
    public function get()
    {
        return $this->storageSetting->firstOrCreate(
            [],
            [
                'provider' => 'local',
                'config' => [],
            ]
        );
    }

    /**
     * Update storage setting (singleton)
     */
    public function update(array $data)
    {
        $setting = $this->get();
        $setting->update($data);
        return $setting;
    }

    /**
     * Get provider configuration fields
     */
    public function getProviderConfigFields($provider)
    {
        $fields = [
            'local' => [],
            's3' => [
                'key' => ['label' => 'Access Key ID', 'type' => 'text', 'required' => true],
                'secret' => ['label' => 'Secret Access Key', 'type' => 'password', 'required' => true],
                'region' => ['label' => 'Region', 'type' => 'text', 'required' => true],
                'bucket' => ['label' => 'Bucket', 'type' => 'text', 'required' => true],
                'endpoint' => ['label' => 'Endpoint (Optional)', 'type' => 'text', 'required' => false],
                'url' => ['label' => 'URL (Optional)', 'type' => 'text', 'required' => false],
                'use_path_style_endpoint' => ['label' => 'Use Path Style Endpoint', 'type' => 'checkbox', 'required' => false],
            ],
            'wasabi' => [
                'key' => ['label' => 'Access Key ID', 'type' => 'text', 'required' => true],
                'secret' => ['label' => 'Secret Access Key', 'type' => 'password', 'required' => true],
                'region' => ['label' => 'Region', 'type' => 'text', 'required' => true],
                'bucket' => ['label' => 'Bucket', 'type' => 'text', 'required' => true],
                'endpoint' => ['label' => 'Endpoint', 'type' => 'text', 'required' => true],
            ],
            'digitalocean' => [
                'key' => ['label' => 'Access Key ID', 'type' => 'text', 'required' => true],
                'secret' => ['label' => 'Secret Access Key', 'type' => 'password', 'required' => true],
                'region' => ['label' => 'Region', 'type' => 'text', 'required' => true],
                'bucket' => ['label' => 'Bucket', 'type' => 'text', 'required' => true],
                'endpoint' => ['label' => 'Endpoint', 'type' => 'text', 'required' => true],
            ],
            'backblaze' => [
                'key' => ['label' => 'Application Key ID', 'type' => 'text', 'required' => true],
                'secret' => ['label' => 'Application Key', 'type' => 'password', 'required' => true],
                'bucket' => ['label' => 'Bucket', 'type' => 'text', 'required' => true],
                'endpoint' => ['label' => 'Endpoint', 'type' => 'text', 'required' => true],
            ],
        ];

        return $fields[$provider] ?? [];
    }
}
