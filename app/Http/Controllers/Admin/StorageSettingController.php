<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStorageSettingRequest;
use App\Services\StorageSettingService;

class StorageSettingController extends Controller
{
    public function __construct(protected StorageSettingService $storageSettingService) {}

    /**
     * Show the form for editing the storage setting (singleton).
     */
    public function edit()
    {
        $storageSetting = $this->storageSettingService->get();
        return view('admin.storage-settings.edit', compact('storageSetting'));
    }

    /**
     * Update the storage setting (singleton).
     */
    public function update(UpdateStorageSettingRequest $request)
    {
        $this->storageSettingService->update($request->validated());

        return redirect()->route('admin.storage-settings.edit')->with('success', __('Storage Setting updated successfully'));
    }
}
