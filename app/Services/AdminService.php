<?php

namespace App\Services;

use App\Exports\AdminExport;
use App\Models\Admin;
use Maatwebsite\Excel\Facades\Excel;

class AdminService
{
    /**
     * Create a new AdminService instance.
     */
    public function __construct(protected Admin $admin) {}

    public function paginate($perPage = 20)
    {
        return $this->admin->with('role')->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->admin->create($data);
    }

    public function update(Admin $admin, array $data)
    {
        return $admin->update($data);
    }

    public function delete($admin, $rows = [])
    {
        if ($admin == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->admin->whereIn('id', $rows)->delete();
            return;
        }
        return $this->admin->delete();
    }

    public function export($filetype)
    {
        return Excel::download(new AdminExport(), 'admins.' . $filetype);
    }
}
