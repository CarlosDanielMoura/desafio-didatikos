<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\City;
use App\Models\Brand;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $city = City::where('COD_CITY', $this->CITY)->first();
        $brand = Brand::where('COD_BRAND', $this->BRAND_PRODUCT)->first();
        return [
            'id' => $this->COD_PRODUCT,
            'nome' => $this->NAME_PRODUCT,
            'valor' => $this->PRICE,
            'marca' => $brand ? strtoupper($brand->NAME_BRAND) : null,
            'estoque' => $this->STOCK,
            'cidade' => $city ? strtoupper($city->NAME_CITY) : null,
        ];
    }
}
