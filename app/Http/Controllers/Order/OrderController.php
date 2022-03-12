<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function getAllOrders(){
        $order = Order::where('user_id', Auth::user()->id)->get();

        return response($order, Response::HTTP_OK);
    }

    public function getOrderWithItems($id)
    {
        $order = Order::with('items')->get()->find($id);

        if (!$order) {
            return response(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        return response($order, Response::HTTP_OK);
    }

    public function lastFiveOrders(){
        $order = Order::where('user_id', Auth::user()->id)->latest()->take(5)->get();

        return response($order, Response::HTTP_OK);
    }

    public function getOrderStats()
    {
        $count = array('total_orders' => 0, 'pending_orders' => 0, 'successful_orders' => 0);
        $count['total_orders'] = Order::where('user_id', Auth()->user()->id)->count();
        $count['pending_orders'] = Order::where('user_id', Auth()->user()->id)->where('payment_status', 'pending')->count();
        $count['successful_orders'] = Order::where('user_id', Auth()->user()->id)->where('payment_status', 'successful')->count();

        return response($count, Response::HTTP_OK);
    }

    // export order csv
    public function exportcsv()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=orders.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () {
            $orders = Cart::get();
            $file = fopen('php://output', 'w');

            fputcsv($file, ['ID', 'Pizza Name', 'Price', 'Quantity']);

            foreach ($orders as $order) {
                fputcsv($file, [$order->id, '', '', '']);

                foreach ($order->items as $item) {
                    fputcsv($file, ['', $item->pizza_name, $item->price, $item->quantity]);
                }
            };
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
