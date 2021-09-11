<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateReviewRequest;
use App\Http\Resources\ReveiwResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PizzaReviewController extends Controller
{
    public function index()
    {
        $review = Review::get();

        return ReveiwResource::collection($review);
    }

    public function show($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response(['message' => 'Review not found']);
        }

        return new ReveiwResource($review);
    }

    public function store(CreateReviewRequest $request)
    {
        Review::create([
            'pizza_id' => $request->pizza_id,
            'user_id' => Auth::user()->id,
            'text' => $request->text
        ]);

        return response(['message' => 'Review created successfully']);
    }

    public function update(Request $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response(['message' => 'Review not found']);
        }

        $review->update($request->all());

        return response(['message' => 'Review updated successfully']);
    }

    public function destroy($id)
    {
        Review::destroy($id);

        return response(['messsage' => 'Review deleted successfully']);
    }
}
