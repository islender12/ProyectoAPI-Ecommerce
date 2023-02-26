<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    // Usamos refreshDatabase para que cada vez que se ejecute un test, el test en la base de datos
    // se refresque
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login()
    {
        // Lo ejecutamos aqui para no estar ejecutandolo en la terminal cada vez que se ejecuta el test
        Artisan::call('passport:install');

        $this->withoutExceptionHandling();

        $user = User::create([
            'fullname' => 'Islender',
            'email' => 'example@example.com',
            'password' => bcrypt("123456")
        ]);

        // Como estamos usando passport debemos crear el token
        $user->createToken('Auth token')->accessToken;


        $response = $this->post(
            route('api.auth.login'),
            [
                'email' => "example@example.com",
                'password' => '123456'
            ]
        );

        // Indicamos que queremos que nos regrese esta petición en este caso queremos
        // Que nos regrese un status satisfactorio status 200
        $response->assertStatus(200);

        // la función $this->assertArrayHasKey se utiliza
        // para verificar si un array contiene una clave específica.
        // en este caso verificará si en el json esta el access token
        // en el caso de no estar puede deberse a un inicio de sesion vacio
        $this->assertArrayHasKey('access_token', $response->json());
    }

    public function test_register()
    {
        Artisan::call('passport:install');
        $this->withoutExceptionHandling();

        $response = $this->post(route('api.register'),
        [
            'fullname' => 'Islender',
            'role' => User::ADMINISTRADOR,
            'email' => 'example@example.com',
            'password' => bcrypt('123456')
        ]);
        // Se espera que cuando se registre no haya ningun error
        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $response->json());
    }
}
