## Cousas interesantes para o futuro
* [Open swoole](https://www.youtube.com/watch?v=nGJOOS1Zd9Q&ab_channel=ThePrimeTime): fai que a aplicación sirva muitisimas peticións mais por segundo

<details>
<summary>Docker</summary>

# Docker
Me cago en dios para levantar esto.
* Esta usando php artisan serve para o servidor
  * Levantao ao levantar o docker porque llo puxen no Dockerfile

## Que facer todos os dias ao arrancar
1. Ir ao docker desktop e borrar os contenedores
2. Arrancalos e build
````shell
docker compose up -d --build
````

Igual tarda un pouco en arrancar a laravel_app, pero o final vai.

## Crear o proxecto de 0
1. Crear `docker-compose.yml`, con php e laravel, mysql e phpmyadmin:
````yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: laravel_app
    working_dir: /var/www
    volumes:
      - ./laravel-app:/var/www
    ports:
      - "8000:8000"
    depends_on:
      - mysql
    networks:
      - laravel

  mysql:
    image: mysql:8.0
    container_name: laravel_mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: laravel
      MYSQL_USER: laravel
      MYSQL_PASSWORD: secret
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - laravel

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: laravel_phpmyadmin
    environment:
      PMA_HOST: mysql
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "8080:80"
    networks:
      - laravel

volumes:
  db_data:

networks:
  laravel:
````

2. Creamos a carpeta do proyecto laravel con:
````shell
docker run --rm [rutaAbsolutaDoDirectorioCoDockerCompose][/carpetaNovaProxectoLaravel]/laravel-app:/app composer create-project laravel/laravel .
````

3. DockerFile
````dockerfile
FROM php:8.2-cli

# Install system dependencies and extensions
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Start the Laravel dev server
CMD ["sh", "-c", "composer install && php artisan serve --host=0.0.0.0 --port=8000"]
````

4. Modificar o `.env` para poñer a conexion a bd
````dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
````

5. Facer a build e iniciar
````shell
docker-compose up --build -d
````
6. AH si e facer as migracións da bd pa ter usuarios sessions e asi:
````shell
docker exec -it laravel_app bash
cd /var/www
php artisan migrate
````
</details>


<details>
<summary>Components</summary>

# Components
Son a mellor forma de reutilizar codigo nas vistas, pequenos trozos de html
que se incluen en outras vistas, podendolle pasar datos.

## Como crealos e chamalos
Creanse en `resources/views/components` e chamanse dende outra vista facendo:
````html
<x-nomeFicheiroComponente ></x-x-nomeFicheiroComponente>
````

## Pasar datos
Hai tipos de datos que lle pasamos aos componentes
* Atributos: todos os atributos html que se queren añadir ao componente. Accedese a eles con `$attributes`
* Slots: os elementos que van ir dentro do componente. Podese acceder:
  * Mediante a variable `$slot`, que pilla todo o contido interior non nomeado
  * Named slots, ponselle un nome a ese contido
* Propiedades: sirven como os argumentos de unha función, son iguales aos atributos,
solo que no valor podeselle poñer logica de php.

#### Ejemplo:
O componente
````injectablephp
@props([
    'active' => false
])

<a
   class="{{ $active ? "bg-gray-900 text-white" : "text-gray-300 hover:bg-gray-700 hover:text-white"}} rounded-md px-3 py-2 text-sm font-medium"
   aria-current="{{ $active ? "true" : "false" }}"
    {{ $attributes }}
>
    {{ $slot }}
</a>
````
Usalo:
````injectablephp
<x-nav-link href="/" :active="request()->is('/')">Dashboard</x-nav-link>
````
</details>

<details>
<summary>Migrations</summary>

# Migracions
Son archivos para interactuar coa estructura da base de datos, tablas, columnas...
* Ubicanse en `database/migrations`

### Crear unha migración
1. Podese crear a man pero recomendase usar o comando:
````shell
php artisan make:migration
````
2. Crearanos o archivo con duas funcions, unha para facer a migración e outra para revertila en caso de ser necesario
   * Neste caso crea a tabla job_listing
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_listing', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->float('salary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listing');
    }
};
````

### Ejecutar migracions
Por muitas que creemos se non as ejecutamos non van facer nada, para ejecutalas:

#### Todas
````shell
php artisan migrate
````
#### Unha en concreto
````shell
php artisan migrate --path --path=database/migrations/2024_04_25_123456_create_jobs_table.php
````
#### Borrar todo e facer as migracions de 0
````shell
php artisan migrate:fresh
````
#### Facer rollback de migracions
Suponse que solo vai afectar as tablas afectadas polas últimas migracions (e borra datos)
````shell
php artisan migrate:rollback
````
Se solo queremos que afecte en concreto as tablas das `2 ultimas`:
````shell
php artisan migrate:rollback --step=2
```` 

</details>

<details>
<summary>Factories</summary>

# Factories
EXTENDER MAIS A INVESTIGACIÓN EN ESTO

Valen para crear instancias de objetos con datos falsos, moi utiles para
test sobretodo ou facer un seed da base de datos.

## Creación
1. Crear unha factory para o modelo Post
````shell
php artisan make:factory PostFactory --model=Post
````
2. Indicar os datos a generar:
````php
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->title,
            'content' => fake()->sentence(),
            'user_id' => User::inRandomOrder()->first()?->id
        ];
    }
}
````

## Uso
Para por ejemplo crear na bd 50 Posts con datos falsos:
````php
Post::factory(50)->create();
````

</details>

<details>
<summary>Models</summary>

# Models


</details>