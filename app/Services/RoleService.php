<?php

namespace App\Services;

use App\Exports\RoleExport;
use App\Models\Role;
use Maatwebsite\Excel\Facades\Excel;

class RoleService
{
    /**
     * Create a new RoleService instance.
     */
    public function __construct(protected Role $role)
    {
        // ...
    }

    public function paginate($perPage = 20)
    {
        return $this->role->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->role->create($data);
    }

    public function update(Role $role, array $data)
    {
        return $role->update($data);
    }

    public function delete($role, $rows = [])
    {
        if ($role == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->role->whereIn('id', $rows)->delete();
            return;
        }
        return $this->role->delete();
    }

    public function export($filetype)
    {
        return Excel::download(new RoleExport(), 'roles.' . $filetype);
    }
}
