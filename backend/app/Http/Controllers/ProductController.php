<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum produto cadastrado',
            ], 404);
        }
        return ProductResource::collection($products);
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
    public function store(ProductRequest $request)
    {
        $product = $request->validated();

        $productisExists = Product::where('NAME_PRODUCT', $product['NAME_PRODUCT'])->where('BRAND_PRODUCT', $product['BRAND_PRODUCT'])->where('CITY', $product['CITY'])->exists();

        if ($productisExists) {
            return response()->json([
                'message' => 'Essa combinação de produto, marca e cidade já existe',
            ], 409);
        }

        try {
            $product = Product::create($product);
            return response()->json([
                'message' => 'Produto cadastrado com sucesso',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'message' => 'Erro ao cadastrar produto',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $productisExists = Product::findOrfail($id);
            return new ProductResource($productisExists);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Produto não encontrado',
            ], 404);
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
    public function update(ProductRequest $request, string $id)
    {
        $product = $request->validated();
        $productisExists = Product::where('NAME_PRODUCT', $product['NAME_PRODUCT'])->where('BRAND_PRODUCT', $product['BRAND_PRODUCT'])->where('CITY', $product['CITY'])->exists();
        if ($productisExists) {
            return response()->json([
                'message' => 'Essa combinação de produto, marca e cidade já existe',
            ], 409);
        }

        try {
            $product = Product::findOrfail($id);
            $product->update($request->all());
            return response()->json([
                'message' => 'Produto atualizado com sucesso',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar produto',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrfail($id);

            $product['STOCK'] = floatval($product['STOCK']);
            if ($product['STOCK'] > 0) {
                return response()->json([
                    'message' => 'Não é possível deletar um produto com estoque',
                ], 409);
            }
            $product->delete();
            return response()->json([
                'message' => 'Produto deletado com sucesso',
                "status" => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao deletar produto',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
