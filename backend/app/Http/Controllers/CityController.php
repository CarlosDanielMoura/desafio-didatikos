<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Product;
use App\Http\Resources\CityResource;
use App\Http\Requests\CityRequest;


class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities =  City::all();
        if ($cities->isEmpty()) {
            return response()->json(["message" => 'Nenhuma cidade encontrada'], 404);
        }
        return CityResource::collection($cities);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request)
    {
        try {
            $cityData = $request->validated();
            $cityData["NAME_CITY"] = mb_strtolower($cityData["NAME_CITY"], 'UTF-8');
            $city = City::where("NAME_CITY", "=", $cityData["NAME_CITY"])->first();
            if ($city) {
                return response()->json(["message" => 'Cidade já cadastrada'], 409);
            }
            return City::create($cityData);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Erro ao cadastrar a cidade'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cityisExists = City::where("COD_CITY", "=", $id)->first();
        if ($cityisExists) {
            return new CityResource($cityisExists);
        }
        return response()->json(["message" => 'Cidade não encontrada'], 404, [], JSON_UNESCAPED_UNICODE);
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
    public function update(CityRequest $request, string $id)
    {
        $city = City::where("COD_CITY", "=", $id)->first();

        if (!$city) {
            return response()->json(["message" => 'Cidade não encontrada'], 404, [], JSON_UNESCAPED_UNICODE);
        }

        $dadosModificados = $request->validated();
        $dadosModificados["NAME_CITY"] = mb_strtolower($dadosModificados["NAME_CITY"], 'UTF-8');

        try {
            $city->update($dadosModificados);
            return response()->json(["message" => 'Cidade modificada com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Erro ao modificar a cidade'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $city = City::findOrFail($id);
            $productsCount = Product::where('CITY', $id)->count();
            if ($productsCount > 0) {
                return response()->json([
                    'message' => 'Não é possível excluir a cidade porque existem produtos associados a ela.',
                ], 400);
            }
            $city->delete();
            return response()->json([
                'message' => 'Cidade excluída com sucesso.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir a cidade.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
