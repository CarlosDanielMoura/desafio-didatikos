<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\City;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CityTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testStoreCity(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $city = City::factory()->make();
        $cityData = ['NAME_CITY' => $city->NAME_CITY];
        $url = 'api/city';

        $cityisExists = City::where('NAME_CITY', $cityData['NAME_CITY'])->exists();
        $response = $this->post($url, $cityData);

        if ($cityisExists) {
            $response->assertStatus(302)
                ->assertJson([
                    'message' => 'Essa cidade já está cadastrada',
                    "errors" => [
                        "NAME_CITY" => [
                            "Essa cidade já está cadastrada0"
                        ]
                    ]
                ]);
        } else {
            $response->assertStatus(201);
        }
    }

    public function testIndexCity(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $url = 'api/city';
        $cities = City::all();
        $response = $this->get($url);

        if ($cities->isEmpty()) {
            $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Nenhuma cidade encontrada'
                ]);
        } else {
            $response->assertStatus(200);
        }
    }

    public function testDestroyCity(): void
    {
        $id_city_consult = 2; // Colocar usuario que existe ou que não existe

        // Cria um usuário usando a Factory
        $user = User::factory()->create();

        // Autentica o usuário para a requisição
        $this->actingAs($user);

        $url = "api/city/" . $id_city_consult;

        // Verifica se existem marcas cadastradas
        $cityisExists = City::where('COD_CITY', $id_city_consult)->first();
        $productsCount = Product::where('CITY', $id_city_consult)->count();

        $response = $this->delete($url);

        if ($productsCount > 0) {
            $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Não é possível excluir a cidade porque existem produtos associados a ela.',
                ]);
        }

        if (!$cityisExists) {
            $response->assertStatus(500)
                ->assertJson([
                    'message' => 'Erro ao excluir a cidade.',
                ]);
        } else {
            $response->assertStatus(200);
            $this->assertDatabaseMissing('city', ['COD_CITY' => $id_city_consult]);
        }
    }

    public function testUpdateCity(): void
    {
        $id_city_consult = 5; // Colocar usuario que existe ou que não existe

        // Cria um usuário usando a Factory
        $user = User::factory()->create();

        // Autentica o usuário para a requisição
        $this->actingAs($user);

        $url = "api/city/" . $id_city_consult;

        // Verifica se existem marcas cadastradas
        $cityisExists = City::where('COD_CITY', $id_city_consult)->first();

        $city = City::factory()->make();
        $cityData = ['NAME_CITY' => $city->NAME_CITY];

        $response = $this->put($url, $cityData);

        if (!$cityisExists) {
            $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Cidade não encontrada',
                ]);
        } else {
            $response->assertStatus(200);
        }
    }

    public function testShowCity(): void
    {
        $id_city_consult = 19; // Colocar usuario que existe ou que não existe

        // Cria um usuário usando a Factory
        $user = User::factory()->create();

        // Autentica o usuário para a requisição
        $this->actingAs($user);

        $url = "api/city/" . $id_city_consult;

        // Verifica se existem marcas cadastradas
        $cityisExists = City::where('COD_CITY', $id_city_consult)->first();

        $response = $this->get($url);

        if (!$cityisExists) {
            $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Cidade não encontrada',
                ]);
        } else {
            $response->assertStatus(200);
            $response->assertJson([
                'data' => [
                    'id' => $id_city_consult,
                    'nome' => mb_strtoupper($cityisExists->NAME_CITY),
                ],
            ]);
        }
    }
}
