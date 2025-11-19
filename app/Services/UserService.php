<?php

namespace App\Services;

use App\Exports\UserExport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class UserService
{
    /**
     * Create a new UserService instance.
     */
    public function __construct(protected User $user)
    {
        // ...
    }

    public function paginate($perPage = 20)
    {
        return $this->user->with('role')->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->user->create($data);
    }

    public function update(User $user, array $data)
    {
        return $user->update($data);
    }

    public function delete($user, $rows = [])
    {
        if ($user == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->user->whereIn('id', $rows)->delete();
            return;
        }
        return $this->user->delete();
    }

    public function export($filetype)
    {
        return Excel::download(new UserExport(), 'users.' . $filetype);
    }
}
