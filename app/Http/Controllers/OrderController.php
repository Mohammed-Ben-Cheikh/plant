<?php

namespace App\Http\Controllers;

use App\Traits\Url;
use App\Models\Order;
use App\Traits\HttpResponses;
use App\Http\Requests\StoreOrderRequest;
use App\Repositories\Contracts\OrderRepository;

class OrderController extends Controller
{
    use HttpResponses, Url;
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $orders = $this->orderRepository->all();
        return $this->success(['Orders' => $orders], 'Orders retrieved successfully', 200);
    }

    public function indexUser()
    {
        $orders = $this->orderRepository->all();
        return $this->success(['Orders' => $orders], 'Orders retrieved successfully', 200);
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $order = $this->orderRepository->create($data);
        return $this->success(['Order' => $order], 'Order created successfully', 201);
    }

    public function show($slug)
    {
        $order = $this->orderRepository->findBySlug($slug);
        if (!$order) {
            return $this->error(null, 'Order not found', 404);
        }
        return $this->success(['Order' => $order], 'Order retrieved successfully');
    }

    public function update(StoreOrderRequest $request, $slug)
    {
        $data = $request->validated();
        $order = $this->orderRepository->findBySlug($slug);
        if (!$order) {
            return $this->error(null, 'Order not found', 404);
        }
        $this->orderRepository->update($order, $data);
        return $this->success(['Order' => $order], 'Order updated successfully');
    }

    public function destroy($slug)
    {
        $this->orderRepository->delete($slug);
        return $this->success(null, 'Order annulled successfully');
    }

    public function userOrders($userId)
    {
        $orders = $this->orderRepository->findByUser($userId);
        return $this->success(['Orders' => $orders], 'Orders retrieved successfully', 200);
    }

    public function orderStatus($status)
    {
        $valideStatus = ['pending', 'success', 'annulled'];
        if (!in_array($status, $valideStatus)) {
            return $this->error(null, 'Invalid status', 400);
        }
        $orders = $this->orderRepository->findByStatus($status);
        return $this->success(['Orders' => $orders], 'Orders retrieved successfully', 200);
    }

    public function updateOrderStatus($slug)
    {
        $order = $this->orderRepository->findBySlug($slug);
        if (!$order) {
            return $this->error(null, 'Order not found', 404);
        }
        $this->orderRepository->update($order, ['status' => 'success']);
        return $this->success(['Order' => $order], 'Order status updated successfully');
    }
}
 