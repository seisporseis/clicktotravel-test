<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('orderItems.product')
        ->where('user_id', Auth::id())
        ->get();

        return response()->json($orders, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        $order = new Order();
        $order->user_id = Auth::id();
        $order->total_amount = $validated['total'];
        $order->status = 0;
        $order->save();

        $orderItems = [];
        foreach($validated['products'] as $product) {
            $orderItems[] = [
                'order_id' => $order->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        OrderItem::insert($orderItems);

        return response()->json([
            'message' => 'Order added successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'You need to login to make an order'], 403);
        }

        return response()->json($order->load('orderItems.product'), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'You need to login to make an order'], 403);
        }

        $order->status = 1; //status changed to processed

        $order->save();

        return response()->json(['message' => 'Order status updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'You need to login to make an order'], 403);
        }

        $order->status = 0; // status change to unprocessed or canceled.

        $order->save();

        return response()->json(['message' => 'Order canceled'], 200);
    }
}
