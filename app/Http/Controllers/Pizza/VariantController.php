<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use App\Http\Requests\VariantRequest;
use App\Http\Resources\VariantResource;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class VariantController extends Controller
{
    public function index()
    {
        $variant = Variant::get();
        return VariantResource::collection($variant);
    }

    public function store(VariantRequest $request)
    {
        $admin = Gate::authorize('delete', 'users');

        if (!$admin) {
            return response(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

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
        $admin = Gate::authorize('delete', 'users');

        if (!$admin) {
            return response(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $variant = Variant::find($id);

        $variant->update($request->only('name'));

        return response(['message' => 'Variant updated successfully']);
    }

    public function destroy($id)
    {
        $admin = Gate::authorize('delete', 'users');

        if (!$admin) {
            return response(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        Variant::destroy($id);

        return response(['message' => 'Variant deleted successfully']);
    }
}
