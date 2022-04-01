<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pizza;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StatsController extends Controller
{
    public function adminStats()
    {
        $admin = Gate::authorize('delete', 'users');

        if (!$admin) {
            return response(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $count = array('users_count' => 0, 'pizza_count' => 0, 'pending_orders_count' => 0, 'successful_orders_count' => 0);
        $count['users_count'] = User::count();
        $count['pizza_count'] = Pizza::count();
        $count['pending_orders_count'] = Order::where('payment_status', 'pending')->count();
        $count['successful_orders_count'] = Order::where('payment_status', 'success')->count();

        return response($count, Response::HTTP_OK);
    }
}