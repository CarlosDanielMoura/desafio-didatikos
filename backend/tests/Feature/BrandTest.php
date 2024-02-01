<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Brand;


use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrandTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testStoreBrand(): void
    {
        // Cria um usuário usando a Factory
        $user = User::factory()->create();

        // Autentica o usuário para a requisição
        $this->actingAs($user);

        // Dados para a criação de uma nova instância de Brand
        $brandData = [
            'NAME_BRAND' => 'Lacoste',
            'MANUFACTURER' => 'Lacoste Polo',
        ];
        $url = "api/brand/";

        // Verifica se a marca já existe
        $brandExists = Brand::where('NAME_BRAND', $brandData['NAME_BRAND'])
            ->where('MANUFACTURER', $brandData['MANUFACTURER'])
            ->exists();
        $response = $this->post($url, $brandData);

        if ($brandExists) {
            $response->assertStatus(409)
                ->assertJson([
                    'message' => 'Essa combinação de marca e fabricante já existe.',
                ]);
        } else {
            $response->assertStatus(201);
        }
    }

    public function testIndexBrand(): void
    {
        // Cria um usuário usando a Factory
        $user = User::factory()->create();

        // Autentica o usuário para a requisição
        $this->actingAs($user);
        $url = "api/brand/";
        // Verifica se existem marcas cadastradas
        $brands = Brand::all();
        $response = $this->get($url);
        if ($brands->isEmpty()) {
            $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Nenhuma marca cadastrada',
                ]);
        } else {
            $response->assertStatus(200);
        }
    }

    public function testDestroyBrand(): void
    {
        $id_brand_consult = 6; // Colocar usuario que existe ou que não existe

        // Cria um usuário usando a Factory
        $user = User::factory()->create();

        // Autentica o usuário para a requisição
        $this->actingAs($user);

        $url = "api/brand/" . $id_brand_consult;

        // Verifica se existem marcas cadastradas
        $brandisExists = Brand::where('COD_BRAND', $id_brand_consult)->first();
        $response = $this->delete($url);
        if (!$brandisExists) {
            $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Marca não encontrada',
                ]);
        } else {
            $response->assertStatus(200);
            $response->assertDatabaseMissing('brand', ['COD_BRAND' => $id_brand_consult]);
        }
    }

    public function testUpdateBrand(): void
    {
        $id_brand_consult = 8; // Colocar usuario que existe ou que não existe

        // Cria um usuário usando a Factory
        $user = User::factory()->create();

        // Autentica o usuário para a requisição
        $this->actingAs($user);
        $brand = Brand::factory()->make();
        $brandDataUpdate = [
            'NAME_BRAND' => $brand->NAME_BRAND,
            'MANUFACTURER' => $brand->MANUFACTURER,
        ];
        $url = "api/brand/" . $id_brand_consult;

        // Verifica se existem marcas cadastradas
        $brandisExists = Brand::where('COD_BRAND', $id_brand_consult)->first();
        $response = $this->put($url, $brandDataUpdate);

        if (!$brandisExists) {
            $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Marca não encontrada',
                ]);
        } else {
            // Converta os dados de atualização para minúsculas
            $brandDataUpdate['NAME_BRAND'] = mb_strtolower($brandDataUpdate['NAME_BRAND']);
            $brandDataUpdate['MANUFACTURER'] = mb_strtolower($brandDataUpdate['MANUFACTURER']);

            // Converta os dados da marca existente para minúsculas
            $existingBrandData = [
                'NAME_BRAND' => mb_strtolower($brandisExists->NAME_BRAND),
                'MANUFACTURER' => mb_strtolower($brandisExists->MANUFACTURER),
            ];

            if ($existingBrandData == $brandDataUpdate) {
                $response->assertStatus(409)
                    ->assertJson([
                        'message' => 'Essa combinação de marca (' . $brandisExists->NAME_BRAND . ') e fabricante (' . $brandisExists->MANUFACTURER . ') já existe.',
                    ]);
            } else {
                $response->assertStatus(200);
            }
        }
    }
    public function testShowBrand(): void
    {
        $id_brand_consult = 6;

        // Cria um usuário usando a Factory
        $user = User::factory()->create();

        // Autentica o usuário para a requisição
        $this->actingAs($user);

        // Define a URL da requisição GET
        $url = "api/brand/" . $id_brand_consult;

        // Verifica se existem marcas cadastradas
        $brandExists = Brand::where('COD_BRAND', $id_brand_consult)->exists();

        $response = $this->get($url);

        if (!$brandExists) {
            $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Marca não encontrada',
                ]);
        } else {
            $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $id_brand_consult,
                        'Nome' => $brandExists->NAME_BRAND,
                        'Fabricante' => $brandExists->MANUFACTURER,
                    ],
                ]);
        }
    }
}
