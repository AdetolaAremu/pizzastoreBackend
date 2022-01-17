<?php

namespace App\Http\Controllers\PaymentController;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function initialize_payment(Request $request)
    {
        $request->validate([
            'city' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $total_amount = auth()->user()->cart->Total;
            $paystack_reference_id = \Paystack::genTranxRef();

            $order = Order::create([
                'order_code' => $paystack_reference_id,
                'user_id' => Auth::user()->id,
                'address_one' => $request->address_one,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'total_amount' => $total_amount,
                'transaction_type' => 'paystack'
            ]);

            for ($i = 0; $i < auth()->user()->cart->items->count(); $i++) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'pizza_id' => auth()->user()->cart->items[$i]->pizza_id,
                    'quantity' => auth()->user()->cart->items[$i]->quantity,
                    'total_amount' => auth()->user()->cart->items[$i]->total_amount,
                    'pizza_price' => auth()->user()->cart->items[$i]->pizza_price,
                    'pizza_name' => auth()->user()->cart->items[$i]->pizza_name
                ]);
            }

            $data = [
                'reference' => $paystack_reference_id,
                'amount' => $total_amount,
                'email' => $request->email ?? auth()->user()->email,
                'metadata' => [
                    'order_id' => $order->id,
                    'reason_for_transaction' => 'Pizza Purchase'
                ]
            ];

            DB::commit();
            return \Paystack::getAuthorizationResponse($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th->getMessage());
        }
    }

    public function finalize_payment()
    {
        return view('payment.paystack');
        $paymentDetails = \Paystack::getPaymentData();
        if($paymentDetails['status']) {
            DB::beginTransaction();
            try {
                $order = Order::where('id', $paymentDetails['data']['metadata']['order_id'])
                    ->where('order_code', $paymentDetails['data']['reference'])
                    ->first();
                $order->payment_status = 'successful';
                $order->save();

                $user = User::find($order->user_id);


                TransactionHistory::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'transaction_type' => 'paystack',
                    'transaction_reference' => $paymentDetails['data']['reference'],
                    'email' => $paymentDetails['data']['customer']['email'],
                    'transaction_status' => 'successful',
                    'raw' => $paymentDetails['data'],
                    'reason_for_transaction' => $paymentDetails['data']['metadata']['reason_for_transaction']
                ]);

                $user->carts->delete();

                DB::commit()
                // return ResponseHelper::success("Payment completed");
                return view('payment.paystack');
            } catch (\Exception $th) {
                DB::rollBack();
                return response($th->getMessage());
            }

        } else {
            $order = Order::where('id', $paymentDetails['data']['metadata']['order_id'])
                ->where('order_code', $paymentDetails['data']['reference'])
                ->first();
            $order->payment_status = 'failed';
            $order->save();

            TransactionHistory::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'transaction_type' => 'paystack',
                'transaction_reference' => $paymentDetails['data']['reference'],
                'email' => $paymentDetails['data']['customer']['email'],
                'transaction_status' => 'failed',
                'raw' => json_encode($paymentDetails['data']),
                'reason_for_transaction' => $paymentDetails['data']['metadata']['reason_for_transaction']
            ]);

            return view('payment.failed');
        }
    }
}
