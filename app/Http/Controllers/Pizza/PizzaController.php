<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use App\Http\Requests\PizzaCreateRequest;
use App\Http\Requests\PizzaUpdateRequest;
use App\Http\Resources\PizzaResource;
use App\Models\Pizza;
use App\Models\PizzaImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PizzaController extends Controller
{
    public function index(Request $request)
    {
        $pizza = Pizza::query();

        if ($s = $request->input('s')) {
            $pizza->whereRaw("name LIKE '%{$s}%'")->orwhereRaw("description LIKE '%{$s}%'");
        }

        return $pizza->with('images','variant')->get();
    }

    public function store(PizzaCreateRequest $request)
    {
        $admin = Gate::authorize('delete', 'users');

        if (!$admin) {
            return response(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $pizza = new Pizza();
            $pizza->name = $request->name;
            $pizza->description = $request->description;
            $pizza->price = $request->price;
            $pizza->variant_id = $request->variant_id;
            // $pizza->featured = 1;
            $pizza->save();

            $documentURL = $request->file('image')->storePublicly('pizza_images', 's3');

            PizzaImage::create([
                'pizza_id' => $pizza->id,
                'image' => basename($documentURL),
                'main' => Storage::disk('s3')->url($documentURL)
            ]);         

            DB::commit();
            return response(['message' => 'Pizza Created Successfully'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th);
        }
    }

    public function show($id)
    {
        $pizza = Pizza::find($id);

        if (!$pizza) {
            return response(['message' => 'Pizza not found'], Response::HTTP_NOT_FOUND);
        }

        return new PizzaResource($pizza);
    }

    public function update(PizzaUpdateRequest $request, $id)
    {
        $admin = Gate::authorize('delete', 'users');

        if (!$admin) {
            return response(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $pizza = Pizza::find($id);

        if (!$pizza) {
            return response(['message' => 'Pizza not found']);
        }

        $pizza->update($request->all());

        return response(['message' => 'Pizza updated successfully'], Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        Gate::authorize('delete', 'users');

        PizzaImage::where('pizza_id', $id)->each(function ($image) {
            Storage::disk('s3')->delete('pizza_images/'.$image->image);
        });

        Pizza::destroy($id);

        return response(["message" => 'Pizza deleted successfully'], Response::HTTP_OK);
    }

    public function getFeatured()
    {
        $featured = Pizza::where('featured', 1)->get();

        return response($featured, Response::HTTP_OK);
    }
}
