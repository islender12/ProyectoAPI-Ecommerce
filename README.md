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

