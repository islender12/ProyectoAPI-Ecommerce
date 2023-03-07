## Desarrollo de Api Rest Ecommerce Usando Metodología TDD


Que es TDD Test Driven Development (Desarrollo Dirigido por Test)
es una práctica de programación que consiste en escribir primero las pruebas (generalmente unitarias), después escribir el código fuente que pase la prueba satisfactoriamente y, por último, refactorizar el código escrito.

1) Escribir una Prueba Fallida.{ Color Rojo}
2) Hacer que prueba Pase { Color Verde }
3) Refactorizar.

###### Para el presente Desarrollo de la API Usaremos Laravel Passport

Para ello Podemos ir a la Documentación de Laravel / Aqui una pequeña documentación:
```
composer require laravel/passport
use Laravel\Passport\HasApiTokens       //Agregamos el trait HasApiTokens al Modelo Usuario

// Y eliminamos o comentamos use Laravel\Sanctum\HasApiTokens
// Finalmente, en config/auth.php definimos un api protector con passport

'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],

```

Ahora debemos agregar nuestras rutas el AuthServiceProvider
Antiguamente se colocaba dentro del metodo boot de la siguiente manera
```
boot()
{
    $this->registerPolicies();

    if(! $this->app->routesAreCached()){
        Passport::routes()
    }
}
```

// El Passport::routes() fue eliminado en Laravel 10 y en su lugar se utiliza la propiedad $registersRoutes para indicar si se deben registrar las rutas de Passport.

ejemplo:

```
public function boot(): void
    {
        $this->registerPolicies();

        Passport::$registersRoutes = true;
    }
}
```
// De esta forma, las rutas de Passport se registrarán automáticamente en tu aplicación Laravel.

Y verificamos que todo marcha correctamente con las rutas php artisan route:list

si vemos que no se listan todas las rutas probemos ejecutar el comando 
php artisan config:cache y luego php artisan route:list


###### Para la creacion de test en laravel usamos el comando 

// en este caso creamos el test de login
```
php artisan make:test nombre-del-test
php artisan make:test AuthTest

```
// dicho comando me ha creado el esqueleto para el test dentro de test\Feature\AuthTest.php

Allí crearemos nuestro test este test y creamos el metodo para realizar nuestro test

```
public function test_login()
{

}

```

//  Como observamos el formato para los test el el siguiente test_nombre-del-test;

```
// Estamos realizando un test de login

 public function test_login()
{
    // Usamos refreshDatabase para que cada vez que se ejecute un test, el test en la base de datos
    // se refresque

     use RefreshDatabase

    // ejecutamos aqui para no estar ejecutandolo en la terminal cada vez que se ejecuta el test
    Artisan::call('passport:install');

    // Nos permite ver el error de una forma mas clara
    $this->withoutExceptionHandling();
    $user = User::create([
        'fullname' => 'Islender',
        'email' => 'example@example.com',
        'password' => bcrypt("123456")
    ]);

    // Como estamos usando passport debemos crear el token
    $user->CreateToken('Auth token')->accessToken;

    $response = $this->post(
        route('api.login'),
        [
            'email' => "example@example.com",
            'password' => '123456'
        ]
    );

    /**
    * la función $response->assertStatus(200) se utiliza para verificar 
    * que una respuesta HTTP tenga el código de estado 200,
    * lo que indica que la solicitud ha sido exitosa.
    */

    $response->assertStatus(200);

        // Queremos que nos regrese el token
        // la función $this->assertArrayHasKey se utiliza
        // para verificar si un array contiene una clave específica.
        
        /**
         * Esta función toma dos argumentos: la clave que se desea buscar 
         * y el array en el que se realizará la búsqueda.
         * Si el array contiene la clave, el test pasará y no ocurrirá nada. 
         * Si el array no contiene la clave, el test fallará y se lanzará una excepción.
         */

        $this->assertArrayHasKey('access_token',$response->json($user));
}
```
// se puede decir que un test nos permite observar el comportamiento o testear
// en este caso la ruta api.login, de otra forma debemos abrir el navegador ingresar email y password
// y ver que nos devuelve. Cosa que podemos hacer de una mejor manera en un test.
// Por ejemplo es como ir a postman hacer una solicitud a una ruta y ver que nos devuelve, en terminos 
// sencillos es algo similar. 

/*
    ejecutamos el test  php artisan test --filter test_login
*/

Como Primer Paso 

// en este caso nos ha dado dos errores:}

1) el campo fullname no exite en la base de datos, lo cambiamos en la migracion
   y en el modelo de name a fullname y ejecutamos migrate:fresh

2) Nos dice que la ruta api.auth.login no existe

-- Route [api.login] not defined.
Para ello como es logico debemos crear esa ruta que en el test nos dio como fallido y que no existia.
Nota: como se trata de una api usamos en routes/api.php

-- Target class [AuthController] does not exist.
// nos dice que dicha clase o dicho controlador no existe
-- Method App\Http\Controllers\AuthController::login does not exist.
El metodo login no existe ............ Creamos el metodo

-- Invalid JSON was returned from the route. // Me dice que un json invalido retorna la ruta 
    y en el metodo lo que hacemos es pues que retorne en json

Recordar estamos haciendo el primer paso del test Escribir una Prueba Fallida.{ Color Rojo}
Asi paso a paso buscamos la forma de que el test pase

Como segundo paso debemos buscar un color verde Hacer que prueba Pase { Color Verde }

Y tercer paso refactorizar


De la misma forma crearemos los test para registro, user ...


