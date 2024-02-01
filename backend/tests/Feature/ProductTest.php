<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testStoreProduct(): void
    {
        $url = '/api/product';
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->make();
        $productData = [
            'NAME_PRODUCT' => $product->NAME_PRODUCT,
            'BRAND_PRODUCT' => $product->BRAND_PRODUCT,
            'CITY' => $product->CITY,
            'PRICE' => $product->PRICE,
            'STOCK' => $product->STOCK,
        ];

        $productisExists = Product::where('NAME_PRODUCT', $productData['NAME_PRODUCT'])->where('BRAND_PRODUCT', $productData['BRAND_PRODUCT'])->where('CITY', $productData['CITY'])->exists();

        $response = $this->post($url, $productData);

        if ($productisExists) {
            $response->assertStatus(409);
            $response->assertJson([
                'message' => 'Essa combinação de produto, marca e cidade já existe',
            ]);
        } else {
            $response->assertStatus(201);
            $response->assertJson([
                'message' => 'Produto cadastrado com sucesso',
                "data" =>
                [
                    "NAME_PRODUCT" => $productData['NAME_PRODUCT'],
                    "BRAND_PRODUCT" => $productData['BRAND_PRODUCT'],
                    "CITY" => $productData['CITY'],
                ]
            ]);
        }
    }
    public function testIndexProduct(): void
    {
        $url = '/api/product';
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get($url);
        $products = Product::all();

        if ($products->isEmpty()) {
            $response->assertStatus(404);
            $response->assertJson([
                'message' => 'Nenhum produto cadastrado',
            ]);
        } else {
            $response->assertStatus(200);
        }
    }
    public function testDestroyProduct(): void
    {
        $randomProduct = \App\Models\Product::inRandomOrder()->first();
        $id_product_consult = $randomProduct->COD_PRODUCT;
        $user = User::factory()->create();
        $this->actingAs($user);
        $url = '/api/product/' . $id_product_consult;
        $response = $this->delete($url);
        $productisExists = Product::where('COD_PRODUCT', $id_product_consult)->first();
        if (!$productisExists) {
            $response = $this->delete($url);
            $response->assertStatus(400);
            $response->assertJson([
                "message" => "Erro ao deletar produto",
            ]);
        } else {
            if ($productisExists["STOCK"] > 0) {
                $response->assertStatus(409);
                $response->assertJson([
                    'message' => 'Não é possível deletar um produto com estoque',
                ]);
            } else {
                $response->assertStatus(200);
                $response->assertJson([
                    "message" => "Produto deletado com sucesso",
                ]);
            }
        }
    }

    public function testUpdateProduct(): void
    {
        $randomProduct = \App\Models\Product::inRandomOrder()->first();
        $id_product_consult = $randomProduct->COD_PRODUCT;
        $user = User::factory()->create();
        $this->actingAs($user);
        $url = '/api/product/' . $id_product_consult;

        // Assuming you have an existing product in the database
        $existingProduct = Product::factory()->create();

        $productData = [
            'NAME_PRODUCT' => $existingProduct->NAME_PRODUCT,
            'BRAND_PRODUCT' => $existingProduct->BRAND_PRODUCT,
            'CITY' => $existingProduct->CITY,
            'PRICE' => $existingProduct->PRICE,
            'STOCK' => $existingProduct->STOCK,
        ];

        $productisExists = Product::where('NAME_PRODUCT', $productData['NAME_PRODUCT'])
            ->where('BRAND_PRODUCT', $productData['BRAND_PRODUCT'])
            ->where('CITY', $productData['CITY'])
            ->exists();

        $response = $this->put($url, $productData);

        if ($productisExists) {
            $response->assertStatus(409)
                ->assertJson([
                    'message' => 'Essa combinação de produto, marca e cidade já existe',
                ]);
        } else {
            $response->assertStatus(200)
                ->assertJson([
                    "message" => "Produto atualizado com sucesso",
                ]);
        }
    }

    public function testShowProduct(): void
    {
        $randomProduct = \App\Models\Product::inRandomOrder()->first();
        $id_product_consult = $randomProduct->COD_PRODUCT;
        $user = User::factory()->create();
        $this->actingAs($user);
        $url = '/api/product/' . $id_product_consult;
        $response = $this->get($url);
        $productisExists = Product::where('COD_PRODUCT', $id_product_consult)->first();

        if (!$productisExists) {
            $response->assertStatus(404);
            $response->assertJson([
                "message" => "Produto não encontrado",
            ]);
        } else {
            $response->assertStatus(200);
        }
    }
}
