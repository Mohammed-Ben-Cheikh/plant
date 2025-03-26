<?php

namespace App\Repositories\data;

use App\Models\Order;
use App\Models\Plants;
use App\Repositories\Contracts\OrderRepository;
use Illuminate\Container\Attributes\Auth;

class OrderRepositoryData implements OrderRepository
{
    /**
     * Get all Order
     *
     * @return mixed
     */
    public function all()
    {
        return Order::all();
    }

    /**
     * Get Order by slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findBySlug(string $slug)
    {
        return Order::where('slug', $slug)->first();
    }

    /**
     * Get Order by user
     *
     * @param int $userId
     * @return mixed
     */
    public function findByUser(int $userId)
    {
        return Order::where('user_id', $userId)->get();
    }

    /**
     * Get Order by status
     *
     * @param string $status
     * @return mixed
     */
    public function findByStatus(string $status)
    {
        return Order::where('status', $status)->get();
    }

    /**
     * Create a new Order
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $plant = Plants::where('id', $data['plant_id'])->first();
        if ($plant->stock < $data['quantity']) {
            return ['message' => 'Stock insuffisant'];
        }
        $user = auth()->user();
        $plant->decrement('stock', $data['quantity']);
        return Order::create([
            'invoice' => 'INV-' . time(),
            'user_id' => $user->id,
            'plant_id' => $plant->id,
            'quantity' => $data['quantity'],
            'total' => $plant->price * $data['quantity']
        ]);
    }

    /**
     * Update a Order
     * 
     * @param array $data
     * @return mixed
     */
    public function update($Order, array $data)
    {
        $Order->update($data);
        return $Order;
    }

    /**
     * Delete a Order
     *
     * @param string $slug
     * @return mixed
     */
    public function delete(string $slug)
    {
        return Order::where('slug', $slug)->update(['status' => 'annulled']);
    }
}
