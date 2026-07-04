<?php

namespace App\Actions\Admin;

use App\Repositories\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllOrdersAction
{
    public function __construct(private readonly OrderRepository $orders) {}

    public function execute(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->orders->getAllFiltered($filters, $perPage);
    }
}