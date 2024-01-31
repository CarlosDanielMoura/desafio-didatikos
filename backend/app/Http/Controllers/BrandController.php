<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Http\Resources\BrandResource;
use App\Models\Product;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $brands = Brand::all();
            if ($brands->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhuma marca cadastrada',
                ], 404);
            }
            return BrandResource::collection($brands);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao listar Marcas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        $brand = $request->validated();
        $brand["NAME_BRAND"] = mb_strtolower($brand["NAME_BRAND"]);
        $brand["MANUFACTURER"] = mb_strtolower($brand["MANUFACTURER"]);

        $brandisExists = Brand::where('NAME_BRAND', $brand['NAME_BRAND'])->where('MANUFACTURER', $brand['MANUFACTURER'])->exists();
        if ($brandisExists) {
            return response()->json([
                'message' => 'Essa combinação de marca e fabricante já existe.',
            ], 409);
        }

        try {
            $brand = Brand::create($brand);
            return response()->json([
                'message' => 'Marca adicionada com sucesso',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao adicionar Marca',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'message' => 'Marca não encontrada',
            ], 404);
        }
        try {
            return new BrandResource($brand);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao buscar Marca',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, string $id)
    {
        $brandMoficado = $request->validated();
        $brandMoficado["NAME_BRAND"] = mb_strtolower($brandMoficado["NAME_BRAND"]);
        $brandMoficado["MANUFACTURER"] = mb_strtolower($brandMoficado["MANUFACTURER"]);
        $brandisExists = Brand::where('COD_BRAND', $id)->first();
        if ($brandisExists) {
            if ($brandisExists->NAME_BRAND == $brandMoficado["NAME_BRAND"] && $brandisExists->MANUFACTURER == $brandMoficado["MANUFACTURER"]) {
                return response()->json([
                    'message' => 'Essa combinação de marca (' . $brandisExists->NAME_BRAND . ') e fabricante (' . $brandisExists->MANUFACTURER . ') já existe.',
                ], 409);
            }
            $brandisExists->update($brandMoficado);
            return response()->json([
                'message' => 'Marca modificada com sucesso',
            ], 200);
        }
        return response()->json([
            'message' => 'Marca não encontrada',
        ], 404);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brandisExists = Brand::where('COD_BRAND', $id)->first();
        $productCount = Product::where('BRAND_PRODUCT', $id)->count();

        if ($productCount > 0) {
            return response()->json([
                'message' => 'Não é possível deletar essa marca, pois ela está associada a um produto',
            ], 409);
        }

        if (!$brandisExists) {
            return response()->json([
                'message' => 'Marca não encontrada',
            ], 404);
        }
        try {
            $brandisExists->delete();
            return response()->json([
                'message' => 'Marca deletada com sucesso',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao deletar Marca',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
