<?php

namespace App\Actions\Admin;

use App\Repositories\MealRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;

class GetDashboardStatsAction
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly UserRepository  $users,
        private readonly MealRepository  $meals,
    ) {}

    public function execute(): array
    {
        $topMeals = $this->meals->topSelling(5)->map(fn ($row) => [
            'meal_id'       => $row->meal_id,
            'name'          => $row->meal?->name,
            'total_ordered' => (int) $row->total_ordered,
        ]);

        return [
            'total_orders'       => $this->orders->countTotal(),
            'orders_today'       => $this->orders->countToday(),
            'revenue_today'      => round($this->orders->revenueToday(), 2),
            'revenue_this_month' => round($this->orders->revenueThisMonth(), 2),
            'total_users'        => $this->users->countTotal(),
            'new_users_today'    => $this->users->countNewToday(),
            'orders_by_status'   => $this->orders->countByStatus(),
            'top_meals'          => $topMeals,
        ];
    }
}