<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Pizza;
use App\Models\PizzaImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(AddToCartRequest $request)
    {
        $user_id = Auth::user()->id;

        if ($pizza = Pizza::find($request->pizza_id)) {
            $pizzaimage = PizzaImage::where(
                'pizza_id',
                $pizza->id
            )->first();

            $user_cart =  Cart::firstOrCreate(['user_id' => $user_id]);

            CartItem::create([
                'cart_id' => $user_cart->id,
                'pizza_id' => $request->pizza_id,
                'pizza_name' => $request->pizza_name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'total_amount' =>  (int) $request->price * (int) $request->quantity,
                'image' => $pizzaimage->image ?? null,
            ]);

            return response(['message' => 'Added to cart'], Response::HTTP_CREATED);
        }
    }

    public function updatecart(Request $request, $cart_item_id)
    {
        $request->validate([
            'quantity' => 'required|min:1'
        ]);

        if ($cart_item = CartItem::find($cart_item_id)) {
            if ($cart_item->cart->user_id != auth('api')->user()->id) {
                return response(['message' => 'You do not have write access to this cart'], Response::HTTP_NOT_FOUND);
            }

            $cart_item->pizza_name = $request->pizza_name;
            $cart_item->pizza_id = $request->pizza_id;
            $cart_item->price = $request->price;
            $cart_item->quantity = $request->quantity;
            $cart_item->total_amount = (int)$request->price * (int)$request->quantity;
            $cart_item->save();

            return response(['message' => 'Cart Updated successfully'], Response::HTTP_ACCEPTED);
        }
    }

    public function getcart()
    {
        // $id = auth('api')->user()->cart->id ?? null;
        // if (!$id) return response(['message' => []]);
        // $cart = Cart::with(['items'])->find($id);
        // return response($cart);
        // use withSum for summing total_amount
        $logged_in_user = Auth()->user()->id;
        $cart = Cart::where('user_id', $logged_in_user)->with('items')->get();
        return CartResource::collection($cart, Response::HTTP_ACCEPTED);
    }

    public function removecart($cart_item_id)
    {
        if ($cart_item = CartItem::find($cart_item_id)) {
            if ($cart_item->cart->user_id != auth('api')->user()->id) {
                return response(['message' => 'You do not have write access to this cart item']);
            }
            $cart_item->delete();
            return response(['message' => 'Item deleted successfully'], Response::HTTP_ACCEPTED);
        } else {
            return response(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function getSpecificCart($id)
    {

        $cart = Cart::find($id);

        if (!$cart) {
            return response(['message' => 'There is no cart with such id'], Response::HTTP_NOT_FOUND);
        }

        return new CartResource($cart, Response::HTTP_ACCEPTED);
        // hello there just to check
    }

    public function emptyCart()
    {
        $cart = Cart::where('user_id', auth('api')->user()->id)->first();
        $cart->delete();
        return response(['message' => 'Cart has been successfully emptied']);
    }
}
