<?php

namespace App\Actions\Admin;

use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllUsersAction
{
    public function __construct(private readonly UserRepository $users) {}

    public function execute(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->users->getAllFiltered($filters, $perPage);
    }
}