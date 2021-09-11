<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use App\Http\Requests\PizzaCreateRequest;
use App\Http\Requests\PizzaUpdateRequest;
use App\Http\Resources\PizzaResource;
use App\Models\Pizza;
use App\Models\PizzaImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PizzaController extends Controller
{
    public function index()
    {
        $pizza = Pizza::get();

        return PizzaResource::collection($pizza);
    }

    public function store(PizzaCreateRequest $request)
    {
        DB::beginTransaction();

        try {
            $pizza = new Pizza();
            $pizza->name = $request->name;
            $pizza->description = $pizza->description;
            $pizza->price = $request->price;
            $pizza->variant_id = $request->variant_id;
            $pizza->save();

            if ($request->pizza_images) {
                for ($i = 0; $i < count($request->pizza_images); $i++) {
                    $file = $request->pizza_images[$i];
                    $imageName = time() . Str::random(10) . '.' . $request->pizza_images[$i]->extension();
                    $path =  'images/pizzas/';
                    $documentURL =  $path . '/' . $imageName;
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
            // Pizza::create([
            //     'name' => $request->name,
            //     'description' => $request->description,
            //     "price" => $request->price,
            //     'variant_id' => $request->variant_id
            // ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response(['message' => 'Ooops, we are missing something']);
        }

        return response(['message' => 'Pizza created successfully']);
    }

    public function show($id)
    {
        $pizza = Pizza::find($id);

        if (!$pizza) {
            return response(['message' => 'Pizza not found']);
        }

        return PizzaResource::collection($pizza);
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
    }
}
