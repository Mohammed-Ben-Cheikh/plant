<?php

namespace App\Http\Controllers;

use App\Traits\Url;
use App\Models\User;
use App\Models\Order;
use App\Models\Plants;
use App\Models\Categories;
use App\Traits\HttpResponses;

class StatistiqueController extends Controller
{
    use HttpResponses, Url;

    public function statistics()
    {
        $param = request('param');
        $acceptedParams = ['orders', 'users', 'data'];
        if (!in_array($param, $acceptedParams)) {
            return $this->error(null, 'Invalid parameter', 400);
        }
        if (!$param) {
            return $this->error(null, 'Parameter required', 400);
        }
        switch ($param) {
            case 'orders':
                $stats = [
                    'total_orders' => Order::count(),
                    'pending_orders' => Order::where('status', 'pending')->count(),
                    'success_orders' => Order::where('status', 'success')->count(),
                    'annulled_orders' => Order::where('status', 'annulled')->count(),
                ];
                return $this->success(['Stats' => $stats], 'Stats retrieved successfully');

            case 'users':
                $stats = [
                    'total_client' => User::where('role_id', '=', 3)->count(),
                    'total_employee' => User::where('role_id', '=', 2)->count(),
                    'total_user' => User::count() - 1,
                ];
                return $this->success(['Stats' => $stats], 'Stats retrieved successfully');

            case 'data':
                $stats = [
                    'Categories' => Categories::count(),
                    'plants' => Plants::count(),
                    'users' => User::count() - 1,
                    'orders' => Order::count(),
                ];
                return $this->success(['Stats' => $stats], 'Stats retrieved successfully');

            default:
                return $this->error(null, 'Invalid parameter', 400);
        }
    }
}
