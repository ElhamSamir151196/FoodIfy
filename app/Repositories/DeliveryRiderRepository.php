<?php

namespace App\Repositories;

use App\Models\DeliveryRider;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DeliveryRiderRepository
{
    public function __construct(protected DeliveryRider $model)
    {
    }

    public function all(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (! empty($filters['available'])) {
            $query->available();
        }

        if (! empty($filters['top_rated'])) {
            $query->topRated((float) $filters['top_rated']);
        }

        if (! empty($filters['transportation_way'])) {
            $query->where('transportation_way', $filters['transportation_way']);
        }

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('phone', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function find(int $id): ?DeliveryRider
    {
        return $this->model->find($id);
    }

    public function create(array $data): DeliveryRider
    {
        return $this->model->create($data);
    }

    public function update(DeliveryRider $rider, array $data): DeliveryRider
    {
        $rider->update($data);

        return $rider->fresh();
    }

    public function delete(DeliveryRider $rider): bool
    {
        return (bool) $rider->delete();
    }
}
