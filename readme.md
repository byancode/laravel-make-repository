# Laravel Repository Pattern

Un paquete simple para implementar el patrón repositorio en Laravel 9, 10 y 11.

## Requisitos

- PHP 8.0 o superior
- Laravel 9.x o superior

## Instalación

### 1. Instalar el paquete mediante Composer

```bash
composer require byancode/laravel-repository
```

El paquete se registrará automáticamente gracias al autodescubrimiento de paquetes de Laravel.

## Uso

### Crear un nuevo repositorio

El comando `make:repository` crea automáticamente un nuevo repositorio en el directorio `App\Repositories`:

```bash
php artisan make:repository UserRepository
```

También puedes especificar el modelo que utilizará el repositorio directamente con la opción `--model` (o `-m`):

```bash
php artisan make:repository UserRepository --model=User
```

Esto generará un repositorio con la clase del modelo ya configurada.

### Implementar un repositorio

Una vez creado el repositorio, debe especificar el modelo que utilizará (si no lo ha hecho con la opción `--model`):

```php
<?php

namespace App\Repositories;

use App\Models\User;
use Byancode\Repository\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * Model class for this repository.
     *
     * @var string
     */
    protected $modelClass = User::class;
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->model = app($this->modelClass);
    }
    
    // Métodos personalizados aquí...
}
```

### Uso básico

Los repositorios incluyen los siguientes métodos básicos:

- `getPaginate($n)`: Obtiene una colección paginada
- `store(array $inputs)`: Crea un nuevo registro
- `getById($id)`: Obtiene un registro por ID
- `update($id, array $inputs)`: Actualiza un registro
- `destroy($id)`: Elimina un registro

### Ejemplo de uso

```php
<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $users;
    
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }
    
    public function index()
    {
        return $this->users->getPaginate(15);
    }
    
    public function store(Request $request)
    {
        return $this->users->store($request->validated());
    }
    
    public function show($id)
    {
        return $this->users->getById($id);
    }
    
    public function update(Request $request, $id)
    {
        $this->users->update($id, $request->validated());
        return $this->users->getById($id);
    }
    
    public function destroy($id)
    {
        return $this->users->destroy($id);
    }
}
```

## Personalización

Puedes extender la clase `BaseRepository` para añadir métodos personalizados según tus necesidades.