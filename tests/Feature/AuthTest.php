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

    public function test_login()
    {
        // Lo ejecutamos aqui para no estar ejecutandolo en la terminal cada vez que se ejecuta el test
        Artisan::call('passport:install');

        $this->withoutExceptionHandling();

        $user = User::create([
            'fullname' => 'Islender',
            'role' => User::CLIENT,
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

        $response = $this->post(
            route('api.register'),
            [
                'fullname' => 'Islender',
                'role' => User::ADMINISTRADOR,
                'email' => 'example@example.com',
                'password' => bcrypt('123456')
            ]
        );
        // Se espera que cuando se registre no haya ningun error y el recurso sea creado
        $response->assertStatus(201); // 201 created La solicitud ha tenido éxito y se ha creado un nuevo recurso como resultado de ello.
        $this->assertArrayHasKey('access_token', $response->json());
    }

    // Test de Validacion de Campos requeridos
    public function test_errorRegister()
    {
        Artisan::call('passport:install');
        $this->withoutExceptionHandling();

        $response = $this->post(route('api.register'), [
            'fullname' => 'Islender',
            'role' => User::ADMINISTRADOR
        ]);

        // Se espera un status 422 unprocessable Content
        // pues el usuario no relleno todos los campos requeridos
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']); // verifica si 'email' , 'password' es un error de validación en formato JSON
    }

    // Test de Validacion de Campos requeridos
    public function test_errorLogin()
    {
        Artisan::call('passport:install');
        $this->withoutExceptionHandling();
        $response = $this->post(route('api.auth.login'), [
            'email' => 'example@example.com'
        ]);

        $response->assertStatus(422); // Status 422 Unprocessable Entity  Indica que el servidor ha entendido la solicitud del cliente, pero no puede procesarla debido a un problema con la entidad enviada en la solicitud
        $response->assertJsonValidationErrors(['password']); // Verifica si 'password' es un error de validacion
    }

    // Test de Credenciales Incorrectas

    public function test_loginAuthError()
    {
        Artisan::call('passport:install');
        $this->withoutExceptionHandling();

        $user = User::create([
            'fullname' => 'Islender',
            'email' => 'example@example.com',
            'password' => bcrypt("123456")
        ]);

        // Como estamos usando passport debemos crear el token
        $user->createToken('Auth token')->accessToken;

        $response = $this->post(route('api.auth.login'), [
            'email' => 'example@example.com',
            'password' => '12534'
        ]);

        $response->assertStatus(401); // status 401 unauthorized pues esta colocando credenciales incorrectas
    }

}
