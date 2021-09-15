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
use Illuminate\Support\Str;

class PizzaController extends Controller
{
    public function index()
    {
        $pizza = Pizza::get();

        return PizzaResource::collection($pizza);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'variant_id' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $pizza = new Pizza();
            $pizza->name = $request->name;
            $pizza->description = $request->description;
            $pizza->price = $request->price;
            $pizza->variant_id = $request->variant_id;
            $pizza->save();

            if ($request->pizza_images) {
                for ($i = 0; $i < count($request->pizza_images); $i++) {
                    $file = $request->pizza_images[$i];
                    $imageName = time() . Str::random(4) . '.' . $request->pizza_images[$i]->extension();
                    $path = "images/pizzas";
                    $documentURL = $path . '/' . $imageName;
                    $file->move($path, $imageName);

                    PizzaImage::create([
                        'pizza_id' => $pizza->id,
                        'image' => $documentURL,
                        'main' => 0
                    ]);
                }
            }

            DB::commit();
            return response(['message' => 'Pizza Created Successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th);
        }
    }

    public function show($id)
    {
        $pizza = Pizza::find($id);

        if (!$pizza) {
            return response(['message' => 'Pizza not found']);
        }

        return new PizzaResource($pizza);
    }

    public function update(PizzaUpdateRequest $request, $id)
    {
        $pizza = Pizza::find($id);

        if (!$pizza) {
            return response(['message' => 'Pizza not found']);
        }

        $pizza->update($request->all());

        return response(['message' => 'Pizza updated successfully']);
    }

    public function destroy($id)
    {
        Pizza::destroy($id);

        return response(["message" => 'Pizza deleted successfully']);
    }
}
