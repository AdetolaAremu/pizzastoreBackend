<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use App\Http\Requests\VariantRequest;
use App\Http\Resources\VariantResource;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function index()
    {
        $variant = Variant::get();
        return VariantResource::collection($variant);
    }

    public function store(VariantRequest $request)
    {
        Variant::create($request->only('name'));

        return response(['message' => 'Variant Variant created']);
    }

    public function show($id)
    {
        $variant = Variant::find($id);

        return new VariantResource($variant);
    }

    public function update(Request $request, $id)
    {
        $variant = Variant::find($id);

        $variant->update($request->only('name'));

        return response(['message' => 'Variant updated successfully']);
    }

    public function destroy($id)
    {
        Variant::destroy($id);

        return response(['message' => 'Variant deleted successfully']);
    }
}
