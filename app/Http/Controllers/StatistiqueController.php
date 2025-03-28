<?php

namespace App\Http\Controllers;

use App\Traits\Url;
use App\Models\User;
use App\Models\Order;
use App\Models\Plants;
use App\Models\Categories;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    use HttpResponses, Url;

    public function statistics()
    {
        $param = request('param');
        $acceptedParams = ['orders', 'users', 'data', 'plants'];
        if (!in_array($param, $acceptedParams)) {
            return $this->error(null, 'Invalid parameter', 400);
        }
        if (!$param) {
            return $this->error(null, 'Parameter required', 400);
        }
        switch ($param) {
            case 'orders':
                $stats = [
                    'total_orders' => DB::table('orders')->count(),
                    'pending_orders' => DB::table('orders')->where('status', 'pending')->count(),
                    'success_orders' => DB::table('orders')->where('status', 'success')->count(),
                    'annulled_orders' => DB::table('orders')->where('status', 'annulled')->count(),
                ];
                return $this->success(['Stats' => $stats], 'Stats retrieved successfully');

            case 'users':
                $stats = [
                    'total_client' => DB::table('users')->where('role_id', 3)->count(),
                    'total_employee' => DB::table('users')->where('role_id', 2)->count(),
                    'total_user' => DB::table('users')->count() - 1,
                ];
                return $this->success(['Stats' => $stats], 'Stats retrieved successfully');

            case 'data':
                $stats = [
                    'Categories' => DB::table('categories')->count(),
                    'plants' => DB::table('plants')->count(),
                    'users' => DB::table('users')->count() - 1,
                    'orders' => DB::table('orders')->count(),
                ];
                return $this->success(['Stats' => $stats], 'Stats retrieved successfully');
            case 'plants':
                $filter = request('filter');
                $stats = [];
                switch ($filter) {
                    case 'best_selling':
                        // Retrieve all categories using Query Builder
                        $categories = DB::table('categories')->get();
                        foreach ($categories as $category) {
                            // Retrieve the top 5 best-selling plants for the current category
                            $bestSelling = DB::table('orders')
                                ->join('plants', 'orders.plant_id', '=', 'plants.id')
                                ->where('plants.category_id', $category->id)
                                ->select('plants.slug', DB::raw('SUM(orders.quantity) as total'))
                                ->groupBy('orders.plant_id', 'plants.slug')
                                ->orderByDesc('total')
                                ->limit(5)
                                ->get();
                            $stats[] = [
                                'category' => $category->slug,
                                'best_selling' => $bestSelling,
                            ];
                        }
                        return $this->success(['Stats' => $stats], 'Stats retrieved successfully');
                    case 'most_ordered':
                        // Retrieve all categories using Query Builder
                        $categories = DB::table('categories')->get();
                        foreach ($categories as $category) {
                            // Retrieve the top 5 most-ordered plants for the current category
                            $mostOrdered = DB::table('orders')
                                ->join('plants', 'orders.plant_id', '=', 'plants.id')
                                ->where('plants.category_id', $category->id)
                                ->select('plants.slug', DB::raw('COUNT(orders.id) as total_orders'))
                                ->groupBy('orders.plant_id', 'plants.slug')
                                ->orderByDesc('total_orders')
                                ->limit(5)
                                ->get();
                            $stats[] = [
                                'category' => $category->slug,
                                'most_ordered' => $mostOrdered,
                            ];
                        }
                        return $this->success(['Stats' => $stats], 'Stats retrieved successfully');
                    default:
                        return $this->error(null, 'Invalid parameter', 400);
                }
            default:
                return $this->error(null, 'Invalid parameter', 400);
        }
    }
}
