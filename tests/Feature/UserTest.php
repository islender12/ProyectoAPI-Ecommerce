<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Guards\TokenGuard;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // Usamos passport install pues estaremos usan refreshdatabase
    public function authenticate()
    {
        Artisan::call('passport:install');

        $user = User::create([
            'fullname' => 'test',
            'role' => User::CLIENT,
            'email' => 'example@example.com',
            'password' => bcrypt('123456')
        ]);

        // Authenticate the User
        Passport::actingAs($user);

        return $user->createToken('Auth token')->accessToken;
    }

    // Test para validar que se puede obtener la informacion de un usuario autenticado
    public function test_a_user_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer' . $token
        ])->get(route('api.auth.me'));

        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $response->json());
    }

    public function test_logout()
    {
        $this->withoutExceptionHandling();
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'bearer' . $token
        ])->post(route('api.logout'));

        $response->assertStatus(200);
        $this->assertArrayHasKey('message', $response->json()); // Esperamos un array llave valor en la respuesta json
        // $this->assertEquals(['message', "Ha Cerrado Sesion Correctamente"], $response->json()); // Vefiricamos que dicho message tenga el Contenido Ha Cerrado.....
    }
}


