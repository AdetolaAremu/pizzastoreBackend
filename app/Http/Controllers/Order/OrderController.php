<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // get the order history for a user
    public function orderHistory()
    {
        $logged_in_user = Auth()->user();
        $order = $logged_in_user->carts()->with('items')->latest()->paginate(10);
        return response($order);
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
