## Cousas interesantes para o futuro
* [Open swoole](https://www.youtube.com/watch?v=nGJOOS1Zd9Q&ab_channel=ThePrimeTime): fai que a aplicación sirva muitisimas peticións mais por segundo
* Laravel Idea: plugin para Laravel
* [Como activar rutas de api en Laravel 12](https://laracasts.com/discuss/channels/laravel/routesapiphp-removed-in-laravel-12-use-web-or-restore-it)
* laravel route model binding: xa carga o model no controlador sen ter que facer find()

<details>
<summary>Utilizades generales</summary>

# Utilidades generales
## Url completa da aplicación
Se queremos unha ruta da nosa aplicación coa url completa, para por exemplo un enlace
nun email, usaremos `url()`.

````php
url('/jobs/'.$job->id) //http://localhost:8000/jobs/204
````
Esto vai funcionar siempre, sustituindo localhost polo servidor no que estea a aplicacion
correndo.
</details>


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
docker run --rm -v [rutaAbsolutaDoDirectorioCoDockerCompose][/carpetaNovaProxectoLaravel]:/app composer create-project laravel/laravel .
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
<summary>Seeders</summary>

# Seeders
Gardanse en `database/seeders`.

Basicamente, son clases que nos sirven para poblar a base de datos. Podemos usar clases
genericas que poblen toda a base de datos(`DatabaseSeeder.php`), ou chamar a unha personalizada
que solo meta datos en certas tablas que nos digamos.

Esto combinado cos factories, fai que poblar a base de datos sexa unha chorrada, porque chamamos
aos factories das clases dentro do seeder e xa fan todo. Tamen podemos chamar a outros seeders.

## Seeders personalizados
Podemos crear seeders personalizados que solo metan datos en x tablas, xa sexa por manter
o codigo mais ordenado, para un test en concreto, modelo en concreto...

1. Facer a clase
````shell
php artisan make:seeder
````
2. Modificala, nesta por ejemplo chamase ao factory de Job e Employee
````php
class JobEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory(30)->create();
        Job::factory(200)->create();
    }
}
````

4. Ahora podemos:
* Chamala dende outros seeders, por ejemplo dende `DatabaseSeeder.php`:
````php
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $this->call(JobEmployeeSeeder::class); //chamamos ao seeder
    }
}
````
* Usala directamente para facer ese seed:
````shell
php artisan db:seed --class=JobEmployeeSeeder
````

## Migracións con seed
Despois de facer unha migración, podemoslle indicar que faga o seed da base de datos.
Ej. migración fresh que fai seed despois de crear toda a estructura
````shell
php artisan migrate:fresh --seed
````
Tamen podemos facer a migración con un seeder en concreto:
````shell
php artisan migrate:fresh --seed --seeder=YourCustomSeeder

````


</details>

<details>
<summary>Models</summary>

# Models
Un modelo non é mais que unha clase que representa unha tabla da base
de datos. Podense establecer relacións entre modelos e facer consultas
sen escribir nada de sql, cousa que para facer CRUDs fai que se fagan
nunha patada.

## Creación
Para crear o modelo podemos usar o comando de artisan, e ademais indicamos
que tamen cree a migración e o factory correspondiente (`-mf`):
````shell
php artisan make:model -mf Proba
````

Exemplo de un modelo que:
* usa o trait de HasFactory para poder usar a factoria
* gardase na tabla `job_listing`
* ten 2 atributos que se poden asignar masivamente ('name','salary')
* ten unha relación `Job N:1 Employee `
````php
class Job extends Model
{
    use HasFactory;

    protected $table = 'job_listing';
    protected $fillable = [
        'name',
        'salary'
    ];

    public function employee(){
        return $this->belongsTo(Employee::class,'idEmployee');
    }
}
````

## Tabla
Para indicar un nombre de tabla distinto, indicase no modelo o atributo `table`.
````php
protected $table = 'job_listing';
````

## Atributos
### Fillable
Para indicar os atributos se poden asignar de forma masiva (usando `create`)
hai que indicalos no atributo `fillable`:
````php
protected $fillable = [
    'name',
    'salary'
];
````
De esta maneira, os atributos que non estean indicados en fillable non se gardarán
ao usar create.
Ej.:
````php
Job::create([
    'name' => 'Jorge',
    'salary' => 5000,
    'isAdmin' => true //este valor non se vai gardar
]);
````
### Guarded
Por outro lado, guarded fai todo o contrario que fillable. Permitiran gardarse todas
os atributos do modelo menos os indicados en guarded:
````php
protected $guarded = [];
````
Neste caso permitiran gardarse todos os atributos do modelo.

## Soft delete
Se queremos que o modelo non se borre realmente da bd ao facer `->delete()` senon que teña un campo
que indique a fecha na que se borrou:
1. Use `SoftDelete` no modelo.
````php
class Post extends Model
{
    use SoftDeletes;
````
2. Na migración da tabla, añadir un campo `->softDeletes()`:
````php
Schema::table('posts', function (Blueprint $table) {
    $table->softDeletes();
});
````

### Como funcionará
````php
$post->delete(); // Sets deleted_at timestamp
Post::all(); // Only where deleted_at IS NULL
Post::withTrashed()->get(); //Include soft-deleted records
Post::onlyTrashed()->get(); //Get only soft-deleted records
$post->restore(); //Restore a soft-deleted record
$post->forceDelete(); //Permanently delete
$post->trashed() //know if a post is softdeleted
````

### Soft delete en relacións
Para que nos dea unha relación que esta soft deleted, hai que indicalo con `withTrashed`:
````php
$user->posts()->withTrashed()->get();
````

Para facer soft deletes ou recuperar tamen das relacións `belongsToMany`:
````php
class User extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        static::deleting(function ($user) {
            if (! $user->isForceDeleting()) {
                $user->posts()->delete();
            }
        });

        static::restoring(function ($user) {
            $user->posts()->withTrashed()->restore();
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
````

## casts()
Se queremos que o atributo sexa dunha forma no modelo pero distinta na bd indicamolo na funcion
`casts()`.

Por ejemplo temos un campo de texto que ten un json na bd, e queremos que cando estea no modelo
sea un array:
1. Na bd
````json
{"theme":"dark","notifications":true}
````
2. Migracion:
````php
Schema::table('users', function (Blueprint $table) {
    $table->json('settings')->nullable();
});
````
3. No modelo:
````php
protected function casts(): array
{
    return [
        'settings' => 'array',
    ];
}
````
4. Ahora podemos facer:
````php
$theme = $user->settings['theme'];
````

## Relacions
Para acceder aos datos dunha relación, crearemos funcions que se chamen igual
que o modelo ao que fai referencia a fk, e que devolverán unha objeto de relación.

Ao crear esta función, poderemos acceder a ela de duas maneiras:
* Property style access(`$job->employee`): que nos vai devolver o objeto Employee da 
relacion
* Method access(`$job->employee()`): vainos devolver o objeto de relación, no cal podemos
aplicar mais funcions de consulta.

### belongsTo (N:1)
Cando se usa na función de un modelo, indica que o modelo é o que ten a fk da relación.

Neste caso, un job terá un employee, e a función solo devolvera un objeto Employee.

````php
public function employee(){
    return $this->belongsTo(Employee::class,'idEmployee');
}
````
* `idEmployee`: indica o nome da fk na tabla jobs (opcional, necesario se indicamos
un nombre de columna non convencional como en este caso)

### hasMany (1:N)
O modelo que a usa NON ten a fk da relación. Vai devolver unha collection de
objetos da clase indicada.

Neste caso un Employee ten multiples Jobs (1:N).
````php
public function jobs(){
    return $this->hasMany(Job::class,'idEmployee');
}
````
* `idEmployee`: indica o nome da columna da tabla jobs que fai referencia a fk de
employes

### belongsToMany (N:N)
Relación na que ambas partes teñen multiples relacións entre elas, usando unha taboa
de relación.

Migración da taboa de relación:
````php
Schema::create('post_tag', function (Blueprint $table) {
    $table->id();
    $table->foreignIdFor(\App\Models\Post::class,'postId')->constrained()
        ->cascadeOnDelete();
    $table->foreignIdFor(\App\Models\Tag::class,'tagId')->constrained()
        ->cascadeOnDelete();
    $table->timestamps();
});
````

Exemplo do metodo dende Post:
````php
public function tags(){
    return $this->belongsToMany(Tag::class, 'post_tag', 'postId', 'tagId');
}
````
Todos estes parametros son necesarios solo se puxemos nomes fora do estantar
* `post_tag`: nome da taboa de relación
* `postId`: nome da columna da tabla de relación que fai referencia a fk do modelo
que no que se esta definindo a función (Post en este caso)
* `tagId`: nome da columna da tabla de relación que fai referencia a fk do modelo da
outra parte da relación (Tag en este caso)
</details>

<details>
<summary>Lazy loading vs eager</summary>

# Lazy loading vs eager
Son maneiras de cargar os datos das relacións no noso programa
* `lazy`(defecto): carganse os datos (faise outra query) solo cando se quere acceder a relación
* `eager`: carganse os datos tanto do modelo como das relacións indicadas todos xuntos

## Lazy
Se non se indica, as relacións cargaranse como lazy.
````php
$job->employee //farase unha query para coller a info da tag
````
## Eager
Cargaranse os datos das relacións indicadas xunto cos modelos:
````php
$jobs = Job::with('employee')->get();
foreach ($jobs as $job) {
    echo $job->employer->name; //non fai mais queries
}
````
Tamen se pode indicar de cargar a relación despois de facer a query:
````php
$jobs = Job::all();
$jobs->load('employer');
````
E se queremos cargar as nested relations tamen podemos, por ejemplo, de cada Employee
tamen cargar o address:
````php
$jobs = Job::with('employee.address')->get();
````

### Cargar todas as relacións
Podemos cargar todas as relacións sen indicar o nome de cada unha con:
````php
$employees = Employee::all()->withRelationshipAutoloading();
````
E se nin siquiera queremos poñer eso, senon que sea o defecto da nosa aplicación
(NON RECOMENDADO) en `AppServiceProvider`:
````php
public function boot(): void
{
    Model::automaticallyEagerLoadRelationships();
}
````


## n+1 query problem
É un problema que ocurre cando cargamos as relacións de maneira `lazy`, é dicir, que
non van estar dispoñibles os datos ata que queremos acceder a eles, momento no que
se fai unha query a bd para obtelos. De ahí o nome n+1, xa que facemos a query para
obter o objeto, e unha query para cada relación.

Ejemplo, por cada Employee, fai unha query para buscar os Job:
````php
$employees = Employee::all();
$employees->each(function ($e){
    $jobs = $e->jobs;
});
````

Para que esto non pase, usaremos o loading `eager`.

### Configurar para que lance error cando se faga lazy loading
En `AppServiceProvider`:
````php
public function boot(): void
{
    Model::preventLazyLoading();
}
````

</details>

<details>
<summary>Pagination</summary>

# Pagination
Se non queremos cargar todos os datos xuntos (recomendado) teremos que usar paginación,
que basicamente aplica un limit con un offset a query.

Ejemplo basico
1. Aplicar paginación na consulta, neste caso de 4 en catro:
````php
$jobs = Job::with('employee')->paginate(4);
````
2. Mostrar os botons para ir a siguiente pagina na vista:
````php
{{ $jobs->links() }}
````

## Formas de paginación
Hai basicamente 3 formas distintas de paginación:
* `paginate()`: a mais costosa en terminos de eficiencia, pero indica o numero de paginas
e podense mover entre as paginas.
* `simplePaginate()`: mais eficiente que paginate xa que non fai un count de todos os resultados.
Solo mostra os botons de atras e siguiente.
* `cursorPaginate()`: a mais eficiente, xa que usa cursores e non OFFSET. Usado en grandes
cantidades de datos que se teñen que actualizar frecuentemente.

❗ Diferencia importante cursorPaginate. En vez de pasar o numero de pagina por a url (`/posts?page=7`)
pasa un cursor(`/posts?cursor=eyJqb2JfbGlzdGluZy5pZCI6NCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ`), que é o pointer en base64 ao ultimo item da pagina actual. Con esto, laravel
sabe dende que item seguir a siguiente pagina, ainda que se añadan mais items a tabla non vai
afectar, cousa que si pasa con paginate e simplePaginate, xa que usan offset.

### Paginate
É o mais lento, pero o mais completo en terminos de usabilidad. Mostra a cantidad de 
resultados e permite navegar mediante o  numero de pagina.
````php
$jobs = Job::with('employee')->paginate(4);
````
### SimplePaginate
Igual que paginate, pero mais eficiente, xa que non fai un count dos resultados.
Permite navegar con botons de atras e adiante.
````php
$jobs = Job::simplePaginate(2);
````

### CursorPaginate
A mais eficiente e robusta, perfecta para aplicacións con scroll infinito ou apis.
Como xa expliquei arriba, usa cursor en vez de offset.

⚠️Os datos da consulta deben de estar ordenador por un campo UNICO e indexado(id por ejemplo),
xa que senon non sabería dende que item seguir na siguiente pagina.

Puntos bos✔️:
* A mais rapida
* Robusta, a paginacion non cambia ainda que se inserten ou borren elementos da tabla.

Contras❌:
* Non se pode acceder a url facilmente, hai que pasar na resposta tanto o siguiente
como o anterior cursor.

Uso:
````php
$jobs = Job::cursorPaginate(2); //pagina de 2 en 2
````

#### Ejemplo de api
Devolve a resposta xunto co anterior e siguiente cursor (null se non hai mais).

Atención ao detalle de por que campos ordena, created_at e id. Non podería ordenar solo
por o campo created_at, xa que pode haber varios registros co mismo valor. Por eso
despois tamen ordena por o id, un campo unico e indexado.
````php
use App\Models\Post;
use Illuminate\Http\Request;

public function index(Request $request)
{
    $posts = Post::orderBy('created_at', 'desc')
                 ->orderBy('id', 'desc')
                 ->cursorPaginate(20);

    return response()->json([
        'data' => $posts->items(),
        'next_cursor' => $posts->nextCursor()?->encode(), // Nullable safe operator
        'prev_cursor' => $posts->previousCursor()?->encode(),
    ]);
}
````
Para entendelo en profundidad. Vamonos poñer no caso que estamos na pagina 3, e o ultimo
item ten o id `123` e fui creado `2025-04-30T10:00:00`:

1. Laravel creará o cursor en base64 a partir do json con estes dous datos:
````json
{
  "created_at": "2025-04-30T10:00:00",
  "id": 123
}
````
2. O que nos daría un cursor:
````php
eyJjcmVhdGVkX2F0IjoiMjAyNS0wNC0zMFQxMDowMDowMCIsImlkIjoxMjN9
````
3. Con ese cursor, ao pasar a pagina 4 fará a siguiente query:
````sql
SELECT * FROM posts
WHERE
    (created_at < '2025-04-30 10:00:00')
   OR (
        created_at = '2025-04-30 10:00:00'
        AND id < 123
    )
ORDER BY created_at DESC, id DESC
LIMIT 21;
````
O where pode parecer algo raro, xa que parece que o OR fai cortocircuito en sql, pero non,
ambas condicions son evaluadas. Ainda así, a logica é a misma, xa que se o created_at
é menor que a fecha do cursor, a segunda condición xa non vai importar, xa que a primeira
true, polo que a fila vaise incluir nos resultados.
Asi que: 
1. Comproba que a fecha sea mais antigua que a do cursor
2. SOLO IMPORTA SE A PRIMEIRA NON SE CUMPLE. Comproba que a fecha sexa igual que a do
cursor, pero o id sexa menor. De esta maneira se hai filas coa misma fecha, incluense
igualmente se o id é menor.

## Uso da paginación en vistas
É moi sencillo, simplemente na vista poñemos:
````php
<div>{{ $jobs->links() }}</div>
````

Laravel por defecto pensará que estamos usando tailwind, asi que se o estamos facendo
xa se vai ver ben de por si os enlaces. Se queremos modificar a forma na que se ven,
hai que facer cambios.

### Personalizar vista de paginación
Para personalizar como se ve a paginación, non podemos facelo directamente, xa que as
vistas de como se ve están en vendor, na carpeta de dependecias de composer, asi que
primeiro hai que facer unha copia da vista de paginación a nosa carpeta publica e 
despois moidicala.

1. Copiar as vistas de paginación:
````shell
php artisan vendor:publish
````
![que seleccionar](imagenesApuntes/img.png)
2. Se imos usar tailwind, podemos deixar solo `tailwind.blade.php` e borrar o resto,
xa que se despois cambiamos por ejemplo por boostrap e non temos as vistas en resources,
simplemente vai mirar na carpeta vendor por elas.
3. Modificamos a vista `tailwind.blade.php` (neste caso) e xa veremos os cambios.
4. (Opcional). Se queremos cambiar para que use por defecto a vista de boostrap5 por ejemplo,
modificaremos `AppServiceProvider`:
````php
public function boot(): void
{
    Paginator::useBootstrapFive();
}
````
</details>

<details>
<summary>Forms</summary>

# Forms
## CSRF
CSRF (Cross Site Request Forgery) é un tipo de ataque no que unha pagina maliciosa
fai un post dende o navegador de un usuario coa sesion iniciada na nosa pagina. 

Poñamos o caso no que un usuario inicia sesion no seu banco, crease a cookie de session non?
Ahora imaginate que entra nunha web maliciosa que fai un post para cambiar a contraseña a ese
mismo banco, de normal non podería xa que tería que iniciar sesion, pero ao existir a cookie
no navegador da victima a aplicación pensa que esta autenticado, e deixalle cambiar a cookie.

### @csfr
Solucionar esto en laravel é moi facil, dentro de cada formulario poremos `@csrf`:
````php
<form method="post" action="/jobs">
        @csrf
````

O que fai esto é crear un campo hidden con un token unico, o cal se enviará xunto co resto de
campos ao POST. Este token crease como atributo dentro da sesion do usuario, e se o token enviado no POST
non coincide laravel devolve un `419`;

</details>

<details>
<summary>Validation</summary>

# Validation
En laravel é moi simple validar formularios e mostrar os errores. Usaremos o metodo validate, ao cal lle 
pasaremos asociativo con atributo => validacions. Se a request non pasa a validación, laravel fai un redirect
back, facendo que:
* o old input sea flasheado na session
* teñamos os errores disponibles en `$errors`

Ejemplo de unha validación simple:
1. No controlador, usaremos `request()->validate` para validar os campos. 
   - Se todo valida, devolvenos un array asociativo co nome do campo e o valor do formulario.
   - Se falla, fai redirect back flasheando a old data na session e pasando os `$errors` a vista.

````php
Route::post('/jobs',function (){
    $validated = request()->validate([
        'name' => ['required', 'min:3'],
        'salary' => ['numeric']
    ]);

    Job::create($validated);

    return redirect('/');
});
````
2. Na vista, despois podemos usar a variable `$errors` directamente (esta sempre dispoñible) para mostrar os errores e 
`old()` para coller os datos antiguos da session.
````php
<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-4">
        <label for="name" class="block text-sm/6 font-medium text-gray-900">Job Name</label>
        <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                <input type="text" value="{{ old('name') }}" name="name" id="name" class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" placeholder="Plumber">
            </div>
        </div>
        @error('name')
            <x-error>{{ $message }}</x-error>
        @enderror
    </div>
</div>
````

## FormRequest personalizados
Podemos crear os nosos Request personalizados, os cales se lle pasarán como argumento ao controlador en vez de o Request
normal.

Con esto, a parte de moita mais reusabilida das reglas de validación, xa nin siquiera teremos que facer o validate dentro
do controlador, laravel faino automaticamente antes de que a request chegue a el:
````php
Route::post('/jobs', function (StoreJobRequest $request) { //se a validacion falla non se executa o controller
    Job::create($request->validated()); //collemos todos os parametros validados
    return redirect('/')->with('success', 'Job created!');
});
````

### Creación
Para crear un novo FormRequest:

1. Creamolo con artisan:
````shell
php artisan make:request StoreJobRequest
````
### Authorize
Donde se inclue a logica que indica que o usuario ten permiso para realizar esa acción ou non. No caso de devolver
false devolvería unha resposta con codigo:
* `403` Forbidden: se esta logueado pero non ten permisos. Ten sentido que por defecto sempre devolva esta e non un
401 Unauthorized(necesitas estar logueado) porque a ruta xa debería ter un middleware que checkeara que esta logueado
antes de chegar ao controlador e ejecutar o FormRequest

Se queremos, podemos cambiar o funcionamiento por defecto facendo Override de `failedAuthorization()`:
````php
protected function failedAuthorization()
{
    throw new AuthorizationException('Son un mensaje meu, tes que estar logueado!!', 401);
}
````

### Rules
Donde se indican as reglas de validación:
````php
public function rules(): array
{
    return [
        'name' => ["required","string","min:3","unique:job_listing,name"],
        "salary" => ["nullable","numeric"]
    ];
}
````
#### Validar custom objects ou arrays
````php
public function rules()
{
    return [
        'items' => 'required|array',
        'items.*.name' => 'required|string',
        'items.*.price' => 'required|numeric|min:0',
    ];
}
````

### PrepareForValidation
Se queremos cambiar os atributos antes de facer a validación, faremolo aquí:
````php
protected function prepareForValidation()
{
    $this->merge([
        'salary' => str_replace(',', '', $this->salary),
    ]);
}
````

## Ciclo de vida das validacions
Para entender ben as validacións, vou explicar ben o ciclo de vida, diferenciando tamen as validacións por api e por web,
as cales devolveran un json ou un redirect back cos errores respectivamente automaticamente gracias ao Handler.

1. Dentro do noso objeto request `StoreJobRequest` teremos as reglas de validación:
````php
class StoreJobRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'min:3'],
            'salary' => ['nullable', 'numeric'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
````
2. Se as validacions fallan, laravel chama ao metodo `failedValidation()`, o cal lanza unha `ValidationException`.
   - podese sobreescribir o metodo failedValidation para cambiar o comportamento
3. Esta excepcion é recollida polo `Handler.php`, o cal comproba se a request espera un json para automaticamente
elegir se facer un redirect back ou mandar un json cos errores.
````php
if ($request->expectsJson()) {
    return $this->invalidJson($request, $exception);
} else {
    return redirect()->back()->withErrors(...)->withInput();
}
````
4. No caso de esperar un json, ejecutase o metodo `invalidJson()`:
````php
protected function invalidJson($request, ValidationException $exception)
{
    return response()->json([
        'message' => $exception->getMessage(),
        'errors' => $exception->errors(),
    ], $exception->status);
}
````

### Que nos permite este comportamento
Que esto funcione así, permitenos usar o mismo objeto Request tanto para a api como para web, tendo solo que escribir
as reglas de validación 1 vez, xestionando o tipo de resposta automaticamente.

`web.php`:
````php
Route::view('/jobs/create', 'jobs.create');
Route::post('/jobs', function (StoreJobRequest $request) {
    Job::create($request->validated());
    return redirect('/')->with('success', 'Job created!');
});
````
`api.php`:
````php
Route::post('/jobs', function (StoreJobRequest $request) {
    $job = Job::create($request->validated());
    return response()->json(['job' => $job], 201);
});
````

</details>

<details>
<summary>Routes</summary>

# Routes
Para ver todas as rutas da aplicación, sen incluir as de vendor:
````shell
php artisan route:list --except-vendor
````

## Route model binding
Se non queremos estar facendo `findOrFail` continuamente nos controladores, podemos facer que se cargue o modelo
automaticamente xa na definición da ruta.

````php
Route::get('/jobs/{job}/edit',function (Job $job){
    return view('jobs.edit',compact('job'));
});
````

Indicar que Laravel ejecuta antes o Model binding que o FormRequest para a validación. Guay!!

Se queremos que o campo polo que busque o modelo na bd non sexa o id, podemos:
1. Indicalo no parametro da ruta:
````php
Route::get('/jobs/{job:name}/edit',function (Job $job){
    return view('jobs.edit',compact('job'));
});
````
2. Indicalo no propio modelo, polo que aplicará a todas as rutas:
````php
public function getRouteKeyName()
{
    return 'name';
}
````

### Customizar o que pasa se non se encontra o resource
Usaremos a funcion missing para indicar que facer (se non queremos o por defecto error 404)
````php
Route::resource('photos', PhotoController::class)
    ->missing(function (Request $request) {
        return Redirect::route('photos.index');
    });
````

</details>


<details>
<summary>Controllers</summary>

# Controllers
Obviamente, non imos poñer toda a logica de cada ruta no arquivo de rutas, para eso creamos controladores,
con funcions que gestionan a logica das rutas.

Para crear un controlador:
````shell
php artisan make:controller
````

## Tipos
Poderemos crear varios tipos:
* **Empty**: crea un controlador vacio
* **Resource**: con todos os metodos necesarios para CRUD
* **Singleton**: igual que resource, pero non pasa o id do modelo a ruta.
* **API**: o mismo que resource, pero sin o `edit` e `create`
* **Invokable**: con un unico metodo `__invoke()`

Ao final solo cambian na cantidad de metodos e nos argumentos que se lle pasan a cada un.

### Empty controller
Creanse manualmente todos os metodos e chamase o controlador como se queira, a pelo:
````php
class JobController extends Controller
{
    public function search($name)
    {
        // Custom logic
    }
}
````
No router:
````php
Route::get('/jobs/search/{name}', [JobController::class, 'search']);
````

### Resource controller
Vai ter todos os metodos necesarios para facer CRUD:
````php
class ExampleController extends Controller
{
    public function index() {}       // GET /resource
    public function create() {}      // GET /resource/create
    public function store(Request $request) {} // POST /resource
    public function show($id) {}     // GET /resource/{id}
    public function edit($id) {}     // GET /resource/{id}/edit
    public function update(Request $request, $id) {} // PUT/PATCH /resource/{id}
    public function destroy($id) {}  // DELETE /resource/{id}
}
````
Ahora para usalo nas rutas, simplemente faremos:
````php
Route::resource('examples', ExampleController::class);
````
Esto vai crear todas as rutas automaticamente cos nomes estandar:

| Verb   | URI                      | Action           | Method      |
| ------ | ------------------------ | ---------------- | ----------- |
| GET    | /examples                | examples.index   | `index()`   |
| GET    | /examples/create         | examples.create  | `create()`  |
| POST   | /examples                | examples.store   | `store()`   |
| GET    | /examples/{example}      | examples.show    | `show()`    |
| GET    | /examples/{example}/edit | examples.edit    | `edit()`    |
| PUT    | /examples/{example}      | examples.update  | `update()`  |
| DELETE | /examples/{example}      | examples.destroy | `destroy()` |

### Excluir rutas
Se non queremos que se creen todas as rutas, podemos excluilas con:
````php
->only(['index']) //solo crea a ruta index
->except(['destroy']); //non crea a ruta da funcion destroy
````
### API controller
Funciona exactamente igual que ResourceController, pero sin os metodos e rutas `create` e `edit`, xa
que a api non os necesita.

Para usalo nas rutas:
````php
Route::apiResource('examples', ExampleApiController::class);
````

### Invokable
Se o controlador solo vai ter un metodo, chamaraselle `__invoke()`:
````php
class ExampleInvokableController extends Controller
{
    public function __invoke(Request $request) {}
}
````

Ahora nas rutas non lle hai que indicar o metodo a ejecutar, laravel xa ejecuta __invoke por defecto:
````php
Route::get('/example', ExampleInvokableController::class);
````

### Singleton
É igual que resources, pero non pasa o id do modelo a ruta, xa que solo pode haber unha ocurrencia.

Por poñer un ejemplo, un usuario ten un `perfil`, non ten sentido a ruta `perfiles/{id}`, tería que ser
directamente `/perfil`. Despois no controlador xa se pilla o perfil a partir do usuario autenticado.

Ejemplo de show:
````php
public function show()
{
    $job = Job::find(1);
    return view('jobs.show',compact('job'));
}
````

#### Uso en rutas
De normal solo se crearán as rutas `show`, `edit`, `update`. Se queremos que tamen se creen as de 
crear e borrar:
* `->creatable()`
* `->destroyable()`
````php
Route::singleton('perfil', PerfilController::class)
    ->creatable()
    ->destroyable();
````

Esto vai crear estas rutas:

| Verb   | URI            | Action         | Controller Method |
| ------ | -------------- | -------------- | ----------------- |
| GET    | /perfil        | perfil.show    | `show()`          |
| GET    | /perfil/create | perfil.create  | `create()`        |
| POST   | /perfil        | perfil.store   | `store()`         |
| GET    | /perfil/edit   | perfil.edit    | `edit()`          |
| DELETE | /perfil        | perfil.destroy | `destroy()`       |

---



</details>

<details>
<summary>Sessions</summary>

# Log in e Registration

Unha maneira xa out of the box de gestionar as sesion é con laravel Breeze.

## Crear usuarios e iniciar sesion
### Crear
````php
public function store(StoreRegistrationRequest $request){
    $user = User::create($request->validated());

    Auth::login($user);

    return redirect('/jobs');
}
````

### Facer login
````php
public function store(Request $request){
    $validated = $request->validate([
        'email' => ['required','email'],
        'password' => ['required']
    ]);

    //try to log in
    $loggedIn = Auth::attempt($validated);
        //back with errors
        if (!$loggedIn){
            throw ValidationException::withMessages([
                "email" => "Those credentials do not match"
            ]);
        }

    $request->session()->regenerate();

    //redirect to dashboard
    return redirect('/');
}
````

## Vistas
### Autenticado ou no
Para renderizar unha cousa ou outra según este ou no autenticado usamos `@guest` e `@auth`:
````php
@guest
    <x-nav-link href="/login" :active="request()->is('login')">Login</x-nav-link>
    <x-nav-link href="/register" :active="request()->is('register')">Register</x-nav-link>
@endguest()

@auth
    <form type="POST" action="/login">
        @csrf
        @method('DELETE')
        <x-form-button>Logout</x-form-button>
    </form>
@endauth()
````

</details>

<details>
<summary>Authorization e persmisos</summary>

# Authorization

Hai varias maneiras de facer autorización:
1. `Inline` authorization: dentro do controlador
2. `Gate`: basicamente funcions definidas con un nombre en `Gate` que devolven true ou false
3. `Middleware`: usar gate, pero a nivel de rutas, polo que xa non chega ao controlador se non a cumple
4. 


## Inline
Facelo directamente no controlador, facendo as comprobacions unha por unha:
````php
public function edit(Job $job)
{
    if (Auth::guest()){
        return redirect('/login');
    }

    if ($job->employee->user->isNot(Auth::user())){
        abort(403);
    }

    return view('jobs.edit',compact('job'));
}
````

## Gate
Crea funcions nomeadas que despois se usan con varias funcions:
* `authorize`: automaticamente fai un abort(403)
* `allows`: devolve true se ten permiso
* `denies`: devolve true se NON ten permiso
* `policies`: basicamente, xuntar as Gates nunha clase para cada Modelo

Indicar que o `User` será o usuario autenticado e é inyectado automaticamente por laravel. Se non esta
autenticado xa nin siquiera ejecuta a función.

````php
Gate::define('edit-job',function (User $user, Job $job){
    return $job->employee->user->is(Auth::user());
});

Gate::authorize('edit-job',$job);

return view('jobs.edit',compact('job'));
````

Esto non é moi util xa que a gate solo esta dispoñible no controlador na que a definamos, por eso se poden
definir no `AppServiceProvider` e estaran dispoñibles en toda a app. Para aplicacions moi pequenas esto pode
valer pero non é nada sostenible.

### Can e cannot
Podemos comprobar se o usuario pode realizar x Gate con `can() e cannot()`:
````php
Auth::user()->can('edit-job',$job)
Auth::user()->cannot('edit-job',$job)
````

Moit util en vistas, para incluir ou non cousas según permisos:
````php
@can('edit-job',$job)
    <div>
        <x-button href="/jobs/{{ $job->id }}/edit">Edit job</x-button>
    </div>
@endcan
````

## Middleware
Usaremos as gates creadas a nivel de ruta

### Can
Podemos chamar a can ao estar creando a ruta, pasandolle os modelos necesarios
````php
Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit')
    ->middleware('auth')
    ->can('edit-job','job'); //aplica a Gate pasandolle o job da ruta
````

## Policy
Para cada modelo, poderemos crear unha Policy, basicamente xuntando todas as Gates que usaremos
para limitar o acceso a ese modelo.

Creanse na carpeta `app/Policies`.

Se seguimos estandares(telo en `app/Policies` e que se chame `NomeModeloPolicy`),
Laravel automaticamente usará esa policy para ese modelo.

Se queremos que siga [outra nomenclatura](https://laravel.com/docs/12.x/authorization#policy-discovery).

### Creación
1. Para crear unha policy para Job:
````shell
php artisan make:policy
````

2. Ejemplo da policy con unha funcion edit:
````php
class JobPolicy
{
    public function edit(User $user, Job $job): bool{
        return $job->employee->user->is($user);
    }
}
````

### Uso
Usanse como as Gates, simplemente en vez do nombre da gate usase o nome do metodo na policy.
A clase policy a usar sabea a partir do modelo, como comentei arriba.

Ejemplo:
````php
Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit')
    ->middleware('auth')
    ->can('edit','job');
````

</details>

<details>
<summary>Emails</summary>

# Envio de correos
Para mandar un correo, usaremos a clase `Mail` (`\Iluminate\Support\Facades\Mail`)

O obxeto que lle pasaremos a esta clase para ser enviado será `Mailable`, o cal representará
o correo a enviar, con toda a info.

## Configuración
Para editar a configuración do envio de mails, faremolo en `config/mail.php`.

### Configuración de credenciales
Para configurar os datos importantes como o host, username e password de correo e asi faise no
`.env`:

````dotenv
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=proba
MAIL_PASSWORD=proba123
MAIL_SCHEME=null
MAIL_FROM_ADDRESS="info@jorgeBlanco.com"
MAIL_FROM_NAME="Jorge Blanco"
````

## Mailable
Normalmente, crearemos unha clase mailable para cada acción na que queremos enviar un mail, por
ejemplo a creación de un novo Job.

### Creacion
Normalmente, crearemos unha clase mailable para cada acción na que queremos enviar un mail, por
ejemplo a creación de un novo Job.

O normal xa é crear a view relacionada con ese mailable no momento de crealo.

1. Crear un mailable:
````php
php artisan make:mail
````
2. Xa teremos a clase e a vista que vai devolver creada.

Dentro da clase creada teremos varios metodos:
- `envelope()`: usada para configurar o asunto do correo
- `content()`: devolvese a vista asociada ao Mailer que conten o contido do correo
- `attachments()`: arquivos adxuntados

#### envelope()
Aquí indicaremos o asunto, e tamen poderemos redefinir cousas como o from e o replyTo
````php
public function envelope(): Envelope
{
    return new Envelope(
        subject: 'Job Posted',
        from: 'outroCorreoDistinto@gmail.com', //non se suele cambiar
        replyTo: 'responderAEste@gmail.com' //nin esto
    );
}
````

#### content()
❗ ATENCIÓN, todas os atributos publicos do objeto Mailable van estar disponibles dentro da
vista que usa content, asi que xa nin llos temos que pasar. Usamos with pa variables non publicas
ou pa cousas que non temos como atributo na clase e queremos ter na vista.

Aqui devolvemos a vista con todo o contido html do correo, poderemoslle pasar parametros
coma siempre fixemos cas vistas:
````php
public function content(): Content
{
    return new Content(
        view: 'mail.job-posted',
        with: [
            'job' => $this->job
        ]
    );
}
````

#### attachments()
Ficheiros adjuntos
````php
public function attachments()
{
    return [
        Attachment::fromPath('/path/to/file.pdf'),
    ];
}
````

## Uso
Para usar o mailable que creamos e mandar o correo:
````php
Mail::to($request->user()->email)->send(
    new JobPosted($job)
);
````

Indicar que nin siquiera fai falta indicar o `->email` do user, se mandamos o user
laravel xa vai coller o seu correo.

</details>

<details>
<summary>Queques</summary>

Enlaces interesantes:
* [queques in production](https://martinjoo.dev/laravel-queues-and-workers-in-production)

# Queques
As queques basicamente é a maneira que ten laravel e php de traballar con 'threads', xa que
php é singlethreaded, non son threads reales, senon que gardanse as tareas
que estan pendientes por facer en base de datos e un worker en execución vainas facendo.

Para a configuración usaremos o archivo `config/queque.php`

Para que os jobs se executen, laravel ten que estar ejecutando continuamente un `worker`.
Para axudar con esto en producción usanse `supervisors`, que fan que pase o que pase 
o worker sempre estea activo.

### Ejecutar un worker
````shell
php artisan queue:work
````

⚠️ cada vez que fagamos un cambio en un job, teremos que reiniciar o worker, xa que este
traballa en memoria e non pillará os cambios

Ejemplo de como mandar un email metendoo na queque:
````php
Mail::to($request->user()->email)->queue(
        new JobPosted($job)
    );
````

## Dedicated job classes
Para crear un job personalizado, usaremos:
````shell
php artisan make:job
````

### Metodos basicos
Basicamente, o metodo `handle()` será o que indique que fai ese job, podemoslle pasar
parámetros no constructor do objeto e usalos.

Clase `TraduceTextJob`:
````php
class TraduceTextJob implements ShouldQueue
{
    use Queueable;

    public String $texto;
    
    public function __construct(String $texto)
    {
        $this->texto = $texto;
    }
    
    public function handle(): void
    {
        logger('Traducindo...'.$this->texto);
    }
}
````
Uso da clase:
````php
TraduceTextJob::dispatch($book->name);
````

</details>

<details>
<summary>Vite e dependencia frontend</summary>

## Depedencias para front
Para manexas as dependencias de front, iremonos o package.json.
````json
{
    "private": true,
    "type": "module",
    "scripts": {
        "build": "vite build",
        "dev": "vite"
    },
    "devDependencies": {
        "@tailwindcss/vite": "^4.0.0",
        "axios": "^1.8.2",
        "concurrently": "^9.0.1",
        "laravel-vite-plugin": "^1.2.0",
        "tailwindcss": "^4.0.0",
        "vite": "^6.2.4" //vite xa ven por defecto
    }
}
````

`scripts` usase para crear shortcuts a comandos, neste caso os de vite. Facendo
`npm run dev` ejecutará o comando vite

1. Para instalar as dependencias, necesitaremos `nodejs` e `npm`:
````shell
apt install nodejs npm -y
````
2. Ahora poderemos instalar as dependencias:
````shell
npm install #na raiz do proyecto
````

ir a unha version mas vella de npm: `npm i -g npm@~10.3`

# Vite
Vite é un dos bundlers mais populares, basicamente o que fan os bundlers é comprimir ao
maximo todos os archivos de frontend no momento de subilos a `producción`. Ademais Vite
tamen trae un liveServer con hard reloading para ver os cambios ao momento.

## Configuración
Para a configuración, usaremos `vite.config.js`.
````js
export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        watch: {
            usePolling: true,
        },
        hmr: {
            host: 'localhost', // Needed for hot reloading to work on host
            //port: 5174,       // The port exposed to your host
            protocol: 'ws',
            clientPort: 5174,
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
````

Explicación rapidilla:
No do server configuramos o servidor de vite que vai conter o assets,
neste caso usamos docker co puerto 5173 mappeado ao 5174, por eso en
hmr:clientport poñemos o 5174, para que o websocket do navegador do 
anfitrion se poda comunicar co servidor

`laravel`: laravel vite plugin onde lle indicamos como integrarse con laravel
  * `input`: indica que ficheiros compilar
  * `refresh`: habilita hot-reloading

</details>

### Ejecución do servidor de pruebas
Necesitaremos nodejs e npm para ejecutar o servidor e instalar os paquetes:
1. Instalar as dependencias
````shell
npm install
````
2. Ejecutar o servidor de pruebas
```shell
npm run dev
```

### Ejecución en producciona
Para facer a build de todo e subilo a producción, faremos:
```shell
npm run build
```

Esto vai comprimir todos os resources usados e serán o que se use en produccion automaticamente.

#### Añadir novos resources
Para añadir novos resources sin que pete ao facer a build, teremos que añadilos a `resources/js/app.js`.
````js
import './bootstrap';

import.meta.glob([
    '../images/**'
]);
````

Aqui por ejemplo colle todas as imagenes da carpeta images.

## Uso no codigo
Para usalo no codigo e que se actualice automaticamente:
```php
<!-- Styles / Scripts -->
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <style>
        /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */@layer theme{:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-serif:ui-serif,Georgia,Cambria,"Times New Roman",Times,serif;--font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;--color-red-50:oklch(.971 .013 17.38);--color-red-100:oklch(.936 .032 17.717);--color-red-200:oklch(.885 .062 18.334);--color-red-300:oklch(.808 .114 19.571);--color-red-400:oklch(.704 .191 22.216);--color-red-500:oklch(.637 .237 25.331);--color-red-600:oklch(.577 .245 27.325);--color-red-700:oklch(.505 .213 27.518);--color-red-800:oklch(.444 .177 26.899);--color-red-900:oklch(.396 .141 25.723);--color-red-950:oklch(.258 .092 26.042);--color-orange-50:oklch(.98 .016 73.684);--color-orange-100:oklch(.954 .038 75.164);--color-orange-200:oklch(.901 .076 70.697);--color-orange-300:oklch(.837 .128 66.29);--color-orange-400:oklch(.75 .183 55.934);--color-orange-500:oklch(.705 .213 47.604);--color-orange-600:oklch(.646 .222 41.116);--color-orange-700:oklch(.553 .195 38.402);--color-orange-800:oklch(.47 .157 37.304);--color-orange-900:oklch(.408 .123 38.172);--color-orange-950:oklch(.266 .079 36.259);--color-amber-50:oklch(.987 .022 95.277);--color-amber-100:oklch(.962 .059 95.617);--color-amber-200:oklch(.924 .12 95.746);--color-amber-300:oklch(.879 .169 91.605);--color-amber-400:oklch(.828 .189 84.429);--color-amber-500:oklch(.769 .188 70.08);--color-amber-600:oklch(.666 .179 58.318);--color-amber-700:oklch(.555 .163 48.998);--color-amber-800:oklch(.473 .137 46.201);--color-amber-900:oklch(.414 .112 45.904);--color-amber-950:oklch(.279 .077 45.635);--color-yellow-50:oklch(.987 .026 102.212);--color-yellow-100:oklch(.973 .071 103.193);--color-yellow-200:oklch(.945 .129 101.54);--color-yellow-300:oklch(.905 .182 98.111);--color-yellow-400:oklch(.852 .199 91.936);--color-yellow-500:oklch(.795 .184 86.047);--color-yellow-600:oklch(.681 .162 75.834);--color-yellow-700:oklch(.554 .135 66.442);--color-yellow-800:oklch(.476 .114 61.907);--color-yellow-900:oklch(.421 .095 57.708);--color-yellow-950:oklch(.286 .066 53.813);--color-lime-50:oklch(.986 .031 120.757);--color-lime-100:oklch(.967 .067 122.328);--color-lime-200:oklch(.938 .127 124.321);--color-lime-300:oklch(.897 .196 126.665);--color-lime-400:oklch(.841 .238 128.85);--color-lime-500:oklch(.768 .233 130.85);--color-lime-600:oklch(.648 .2 131.684);--color-lime-700:oklch(.532 .157 131.589);--color-lime-800:oklch(.453 .124 130.933);--color-lime-900:oklch(.405 .101 131.063);--color-lime-950:oklch(.274 .072 132.109);--color-green-50:oklch(.982 .018 155.826);--color-green-100:oklch(.962 .044 156.743);--color-green-200:oklch(.925 .084 155.995);--color-green-300:oklch(.871 .15 154.449);--color-green-400:oklch(.792 .209 151.711);--color-green-500:oklch(.723 .219 149.579);--color-green-600:oklch(.627 .194 149.214);--color-green-700:oklch(.527 .154 150.069);--color-green-800:oklch(.448 .119 151.328);--color-green-900:oklch(.393 .095 152.535);--color-green-950:oklch(.266 .065 152.934);--color-emerald-50:oklch(.979 .021 166.113);--color-emerald-100:oklch(.95 .052 163.051);--color-emerald-200:oklch(.905 .093 164.15);--color-emerald-300:oklch(.845 .143 164.978);--color-emerald-400:oklch(.765 .177 163.223);--color-emerald-500:oklch(.696 .17 162.48);--color-emerald-600:oklch(.596 .145 163.225);--color-emerald-700:oklch(.508 .118 165.612);--color-emerald-800:oklch(.432 .095 166.913);--color-emerald-900:oklch(.378 .077 168.94);--color-emerald-950:oklch(.262 .051 172.552);--color-teal-50:oklch(.984 .014 180.72);--color-teal-100:oklch(.953 .051 180.801);--color-teal-200:oklch(.91 .096 180.426);--color-teal-300:oklch(.855 .138 181.071);--color-teal-400:oklch(.777 .152 181.912);--color-teal-500:oklch(.704 .14 182.503);--color-teal-600:oklch(.6 .118 184.704);--color-teal-700:oklch(.511 .096 186.391);--color-teal-800:oklch(.437 .078 188.216);--color-teal-900:oklch(.386 .063 188.416);--color-teal-950:oklch(.277 .046 192.524);--color-cyan-50:oklch(.984 .019 200.873);--color-cyan-100:oklch(.956 .045 203.388);--color-cyan-200:oklch(.917 .08 205.041);--color-cyan-300:oklch(.865 .127 207.078);--color-cyan-400:oklch(.789 .154 211.53);--color-cyan-500:oklch(.715 .143 215.221);--color-cyan-600:oklch(.609 .126 221.723);--color-cyan-700:oklch(.52 .105 223.128);--color-cyan-800:oklch(.45 .085 224.283);--color-cyan-900:oklch(.398 .07 227.392);--color-cyan-950:oklch(.302 .056 229.695);--color-sky-50:oklch(.977 .013 236.62);--color-sky-100:oklch(.951 .026 236.824);--color-sky-200:oklch(.901 .058 230.902);--color-sky-300:oklch(.828 .111 230.318);--color-sky-400:oklch(.746 .16 232.661);--color-sky-500:oklch(.685 .169 237.323);--color-sky-600:oklch(.588 .158 241.966);--color-sky-700:oklch(.5 .134 242.749);--color-sky-800:oklch(.443 .11 240.79);--color-sky-900:oklch(.391 .09 240.876);--color-sky-950:oklch(.293 .066 243.157);--color-blue-50:oklch(.97 .014 254.604);--color-blue-100:oklch(.932 .032 255.585);--color-blue-200:oklch(.882 .059 254.128);--color-blue-300:oklch(.809 .105 251.813);--color-blue-400:oklch(.707 .165 254.624);--color-blue-500:oklch(.623 .214 259.815);--color-blue-600:oklch(.546 .245 262.881);--color-blue-700:oklch(.488 .243 264.376);--color-blue-800:oklch(.424 .199 265.638);--color-blue-900:oklch(.379 .146 265.522);--color-blue-950:oklch(.282 .091 267.935);--color-indigo-50:oklch(.962 .018 272.314);--color-indigo-100:oklch(.93 .034 272.788);--color-indigo-200:oklch(.87 .065 274.039);--color-indigo-300:oklch(.785 .115 274.713);--color-indigo-400:oklch(.673 .182 276.935);--color-indigo-500:oklch(.585 .233 277.117);--color-indigo-600:oklch(.511 .262 276.966);--color-indigo-700:oklch(.457 .24 277.023);--color-indigo-800:oklch(.398 .195 277.366);--color-indigo-900:oklch(.359 .144 278.697);--color-indigo-950:oklch(.257 .09 281.288);--color-violet-50:oklch(.969 .016 293.756);--color-violet-100:oklch(.943 .029 294.588);--color-violet-200:oklch(.894 .057 293.283);--color-violet-300:oklch(.811 .111 293.571);--color-violet-400:oklch(.702 .183 293.541);--color-violet-500:oklch(.606 .25 292.717);--color-violet-600:oklch(.541 .281 293.009);--color-violet-700:oklch(.491 .27 292.581);--color-violet-800:oklch(.432 .232 292.759);--color-violet-900:oklch(.38 .189 293.745);--color-violet-950:oklch(.283 .141 291.089);--color-purple-50:oklch(.977 .014 308.299);--color-purple-100:oklch(.946 .033 307.174);--color-purple-200:oklch(.902 .063 306.703);--color-purple-300:oklch(.827 .119 306.383);--color-purple-400:oklch(.714 .203 305.504);--color-purple-500:oklch(.627 .265 303.9);--color-purple-600:oklch(.558 .288 302.321);--color-purple-700:oklch(.496 .265 301.924);--color-purple-800:oklch(.438 .218 303.724);--color-purple-900:oklch(.381 .176 304.987);--color-purple-950:oklch(.291 .149 302.717);--color-fuchsia-50:oklch(.977 .017 320.058);--color-fuchsia-100:oklch(.952 .037 318.852);--color-fuchsia-200:oklch(.903 .076 319.62);--color-fuchsia-300:oklch(.833 .145 321.434);--color-fuchsia-400:oklch(.74 .238 322.16);--color-fuchsia-500:oklch(.667 .295 322.15);--color-fuchsia-600:oklch(.591 .293 322.896);--color-fuchsia-700:oklch(.518 .253 323.949);--color-fuchsia-800:oklch(.452 .211 324.591);--color-fuchsia-900:oklch(.401 .17 325.612);--color-fuchsia-950:oklch(.293 .136 325.661);--color-pink-50:oklch(.971 .014 343.198);--color-pink-100:oklch(.948 .028 342.258);--color-pink-200:oklch(.899 .061 343.231);--color-pink-300:oklch(.823 .12 346.018);--color-pink-400:oklch(.718 .202 349.761);--color-pink-500:oklch(.656 .241 354.308);--color-pink-600:oklch(.592 .249 .584);--color-pink-700:oklch(.525 .223 3.958);--color-pink-800:oklch(.459 .187 3.815);--color-pink-900:oklch(.408 .153 2.432);--color-pink-950:oklch(.284 .109 3.907);--color-rose-50:oklch(.969 .015 12.422);--color-rose-100:oklch(.941 .03 12.58);--color-rose-200:oklch(.892 .058 10.001);--color-rose-300:oklch(.81 .117 11.638);--color-rose-400:oklch(.712 .194 13.428);--color-rose-500:oklch(.645 .246 16.439);--color-rose-600:oklch(.586 .253 17.585);--color-rose-700:oklch(.514 .222 16.935);--color-rose-800:oklch(.455 .188 13.697);--color-rose-900:oklch(.41 .159 10.272);--color-rose-950:oklch(.271 .105 12.094);--color-slate-50:oklch(.984 .003 247.858);--color-slate-100:oklch(.968 .007 247.896);--color-slate-200:oklch(.929 .013 255.508);--color-slate-300:oklch(.869 .022 252.894);--color-slate-400:oklch(.704 .04 256.788);--color-slate-500:oklch(.554 .046 257.417);--color-slate-600:oklch(.446 .043 257.281);--color-slate-700:oklch(.372 .044 257.287);--color-slate-800:oklch(.279 .041 260.031);--color-slate-900:oklch(.208 .042 265.755);--color-slate-950:oklch(.129 .042 264.695);--color-gray-50:oklch(.985 .002 247.839);--color-gray-100:oklch(.967 .003 264.542);--color-gray-200:oklch(.928 .006 264.531);--color-gray-300:oklch(.872 .01 258.338);--color-gray-400:oklch(.707 .022 261.325);--color-gray-500:oklch(.551 .027 264.364);--color-gray-600:oklch(.446 .03 256.802);--color-gray-700:oklch(.373 .034 259.733);--color-gray-800:oklch(.278 .033 256.848);--color-gray-900:oklch(.21 .034 264.665);--color-gray-950:oklch(.13 .028 261.692);--color-zinc-50:oklch(.985 0 0);--color-zinc-100:oklch(.967 .001 286.375);--color-zinc-200:oklch(.92 .004 286.32);--color-zinc-300:oklch(.871 .006 286.286);--color-zinc-400:oklch(.705 .015 286.067);--color-zinc-500:oklch(.552 .016 285.938);--color-zinc-600:oklch(.442 .017 285.786);--color-zinc-700:oklch(.37 .013 285.805);--color-zinc-800:oklch(.274 .006 286.033);--color-zinc-900:oklch(.21 .006 285.885);--color-zinc-950:oklch(.141 .005 285.823);--color-neutral-50:oklch(.985 0 0);--color-neutral-100:oklch(.97 0 0);--color-neutral-200:oklch(.922 0 0);--color-neutral-300:oklch(.87 0 0);--color-neutral-400:oklch(.708 0 0);--color-neutral-500:oklch(.556 0 0);--color-neutral-600:oklch(.439 0 0);--color-neutral-700:oklch(.371 0 0);--color-neutral-800:oklch(.269 0 0);--color-neutral-900:oklch(.205 0 0);--color-neutral-950:oklch(.145 0 0);--color-stone-50:oklch(.985 .001 106.423);--color-stone-100:oklch(.97 .001 106.424);--color-stone-200:oklch(.923 .003 48.717);--color-stone-300:oklch(.869 .005 56.366);--color-stone-400:oklch(.709 .01 56.259);--color-stone-500:oklch(.553 .013 58.071);--color-stone-600:oklch(.444 .011 73.639);--color-stone-700:oklch(.374 .01 67.558);--color-stone-800:oklch(.268 .007 34.298);--color-stone-900:oklch(.216 .006 56.043);--color-stone-950:oklch(.147 .004 49.25);--color-black:#000;--color-white:#fff;--spacing:.25rem;--breakpoint-sm:40rem;--breakpoint-md:48rem;--breakpoint-lg:64rem;--breakpoint-xl:80rem;--breakpoint-2xl:96rem;--container-3xs:16rem;--container-2xs:18rem;--container-xs:20rem;--container-sm:24rem;--container-md:28rem;--container-lg:32rem;--container-xl:36rem;--container-2xl:42rem;--container-3xl:48rem;--container-4xl:56rem;--container-5xl:64rem;--container-6xl:72rem;--container-7xl:80rem;--text-xs:.75rem;--text-xs--line-height:calc(1/.75);--text-sm:.875rem;--text-sm--line-height:calc(1.25/.875);--text-base:1rem;--text-base--line-height: 1.5 ;--text-lg:1.125rem;--text-lg--line-height:calc(1.75/1.125);--text-xl:1.25rem;--text-xl--line-height:calc(1.75/1.25);--text-2xl:1.5rem;--text-2xl--line-height:calc(2/1.5);--text-3xl:1.875rem;--text-3xl--line-height: 1.2 ;--text-4xl:2.25rem;--text-4xl--line-height:calc(2.5/2.25);--text-5xl:3rem;--text-5xl--line-height:1;--text-6xl:3.75rem;--text-6xl--line-height:1;--text-7xl:4.5rem;--text-7xl--line-height:1;--text-8xl:6rem;--text-8xl--line-height:1;--text-9xl:8rem;--text-9xl--line-height:1;--font-weight-thin:100;--font-weight-extralight:200;--font-weight-light:300;--font-weight-normal:400;--font-weight-medium:500;--font-weight-semibold:600;--font-weight-bold:700;--font-weight-extrabold:800;--font-weight-black:900;--tracking-tighter:-.05em;--tracking-tight:-.025em;--tracking-normal:0em;--tracking-wide:.025em;--tracking-wider:.05em;--tracking-widest:.1em;--leading-tight:1.25;--leading-snug:1.375;--leading-normal:1.5;--leading-relaxed:1.625;--leading-loose:2;--radius-xs:.125rem;--radius-sm:.25rem;--radius-md:.375rem;--radius-lg:.5rem;--radius-xl:.75rem;--radius-2xl:1rem;--radius-3xl:1.5rem;--radius-4xl:2rem;--shadow-2xs:0 1px #0000000d;--shadow-xs:0 1px 2px 0 #0000000d;--shadow-sm:0 1px 3px 0 #0000001a,0 1px 2px -1px #0000001a;--shadow-md:0 4px 6px -1px #0000001a,0 2px 4px -2px #0000001a;--shadow-lg:0 10px 15px -3px #0000001a,0 4px 6px -4px #0000001a;--shadow-xl:0 20px 25px -5px #0000001a,0 8px 10px -6px #0000001a;--shadow-2xl:0 25px 50px -12px #00000040;--inset-shadow-2xs:inset 0 1px #0000000d;--inset-shadow-xs:inset 0 1px 1px #0000000d;--inset-shadow-sm:inset 0 2px 4px #0000000d;--drop-shadow-xs:0 1px 1px #0000000d;--drop-shadow-sm:0 1px 2px #00000026;--drop-shadow-md:0 3px 3px #0000001f;--drop-shadow-lg:0 4px 4px #00000026;--drop-shadow-xl:0 9px 7px #0000001a;--drop-shadow-2xl:0 25px 25px #00000026;--ease-in:cubic-bezier(.4,0,1,1);--ease-out:cubic-bezier(0,0,.2,1);--ease-in-out:cubic-bezier(.4,0,.2,1);--animate-spin:spin 1s linear infinite;--animate-ping:ping 1s cubic-bezier(0,0,.2,1)infinite;--animate-pulse:pulse 2s cubic-bezier(.4,0,.6,1)infinite;--animate-bounce:bounce 1s infinite;--blur-xs:4px;--blur-sm:8px;--blur-md:12px;--blur-lg:16px;--blur-xl:24px;--blur-2xl:40px;--blur-3xl:64px;--perspective-dramatic:100px;--perspective-near:300px;--perspective-normal:500px;--perspective-midrange:800px;--perspective-distant:1200px;--aspect-video:16/9;--default-transition-duration:.15s;--default-transition-timing-function:cubic-bezier(.4,0,.2,1);--default-font-family:var(--font-sans);--default-font-feature-settings:var(--font-sans--font-feature-settings);--default-font-variation-settings:var(--font-sans--font-variation-settings);--default-mono-font-family:var(--font-mono);--default-mono-font-feature-settings:var(--font-mono--font-feature-settings);--default-mono-font-variation-settings:var(--font-mono--font-variation-settings)}}@layer base{*,:after,:before,::backdrop{box-sizing:border-box;border:0 solid;margin:0;padding:0}::file-selector-button{box-sizing:border-box;border:0 solid;margin:0;padding:0}html,:host{-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;line-height:1.5;font-family:var(--default-font-family,ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji");font-feature-settings:var(--default-font-feature-settings,normal);font-variation-settings:var(--default-font-variation-settings,normal);-webkit-tap-highlight-color:transparent}body{line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;-webkit-text-decoration:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,samp,pre{font-family:var(--default-mono-font-family,ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace);font-feature-settings:var(--default-mono-font-feature-settings,normal);font-variation-settings:var(--default-mono-font-variation-settings,normal);font-size:1em}small{font-size:80%}sub,sup{vertical-align:baseline;font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}:-moz-focusring{outline:auto}progress{vertical-align:baseline}summary{display:list-item}ol,ul,menu{list-style:none}img,svg,video,canvas,audio,iframe,embed,object{vertical-align:middle;display:block}img,video{max-width:100%;height:auto}button,input,select,optgroup,textarea{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}::file-selector-button{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}:where(select:is([multiple],[size])) optgroup{font-weight:bolder}:where(select:is([multiple],[size])) optgroup option{padding-inline-start:20px}::file-selector-button{margin-inline-end:4px}::placeholder{opacity:1;color:color-mix(in oklab,currentColor 50%,transparent)}textarea{resize:vertical}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-date-and-time-value{min-height:1lh;text-align:inherit}::-webkit-datetime-edit{display:inline-flex}::-webkit-datetime-edit-fields-wrapper{padding:0}::-webkit-datetime-edit{padding-block:0}::-webkit-datetime-edit-year-field{padding-block:0}::-webkit-datetime-edit-month-field{padding-block:0}::-webkit-datetime-edit-day-field{padding-block:0}::-webkit-datetime-edit-hour-field{padding-block:0}::-webkit-datetime-edit-minute-field{padding-block:0}::-webkit-datetime-edit-second-field{padding-block:0}::-webkit-datetime-edit-millisecond-field{padding-block:0}::-webkit-datetime-edit-meridiem-field{padding-block:0}:-moz-ui-invalid{box-shadow:none}button,input:where([type=button],[type=reset],[type=submit]){-webkit-appearance:button;-moz-appearance:button;appearance:button}::file-selector-button{-webkit-appearance:button;-moz-appearance:button;appearance:button}::-webkit-inner-spin-button{height:auto}::-webkit-outer-spin-button{height:auto}[hidden]:where(:not([hidden=until-found])){display:none!important}}@layer components;@layer utilities{.absolute{position:absolute}.relative{position:relative}.static{position:static}.inset-0{inset:calc(var(--spacing)*0)}.-mt-\[4\.9rem\]{margin-top:-4.9rem}.-mb-px{margin-bottom:-1px}.mb-1{margin-bottom:calc(var(--spacing)*1)}.mb-2{margin-bottom:calc(var(--spacing)*2)}.mb-4{margin-bottom:calc(var(--spacing)*4)}.mb-6{margin-bottom:calc(var(--spacing)*6)}.-ml-8{margin-left:calc(var(--spacing)*-8)}.flex{display:flex}.hidden{display:none}.inline-block{display:inline-block}.inline-flex{display:inline-flex}.table{display:table}.aspect-\[335\/376\]{aspect-ratio:335/376}.h-1{height:calc(var(--spacing)*1)}.h-1\.5{height:calc(var(--spacing)*1.5)}.h-2{height:calc(var(--spacing)*2)}.h-2\.5{height:calc(var(--spacing)*2.5)}.h-3{height:calc(var(--spacing)*3)}.h-3\.5{height:calc(var(--spacing)*3.5)}.h-14{height:calc(var(--spacing)*14)}.h-14\.5{height:calc(var(--spacing)*14.5)}.min-h-screen{min-height:100vh}.w-1{width:calc(var(--spacing)*1)}.w-1\.5{width:calc(var(--spacing)*1.5)}.w-2{width:calc(var(--spacing)*2)}.w-2\.5{width:calc(var(--spacing)*2.5)}.w-3{width:calc(var(--spacing)*3)}.w-3\.5{width:calc(var(--spacing)*3.5)}.w-\[448px\]{width:448px}.w-full{width:100%}.max-w-\[335px\]{max-width:335px}.max-w-none{max-width:none}.flex-1{flex:1}.shrink-0{flex-shrink:0}.translate-y-0{--tw-translate-y:calc(var(--spacing)*0);translate:var(--tw-translate-x)var(--tw-translate-y)}.transform{transform:var(--tw-rotate-x)var(--tw-rotate-y)var(--tw-rotate-z)var(--tw-skew-x)var(--tw-skew-y)}.flex-col{flex-direction:column}.flex-col-reverse{flex-direction:column-reverse}.items-center{align-items:center}.justify-center{justify-content:center}.justify-end{justify-content:flex-end}.gap-3{gap:calc(var(--spacing)*3)}.gap-4{gap:calc(var(--spacing)*4)}:where(.space-x-1>:not(:last-child)){--tw-space-x-reverse:0;margin-inline-start:calc(calc(var(--spacing)*1)*var(--tw-space-x-reverse));margin-inline-end:calc(calc(var(--spacing)*1)*calc(1 - var(--tw-space-x-reverse)))}.overflow-hidden{overflow:hidden}.rounded-full{border-radius:3.40282e38px}.rounded-sm{border-radius:var(--radius-sm)}.rounded-t-lg{border-top-left-radius:var(--radius-lg);border-top-right-radius:var(--radius-lg)}.rounded-br-lg{border-bottom-right-radius:var(--radius-lg)}.rounded-bl-lg{border-bottom-left-radius:var(--radius-lg)}.border{border-style:var(--tw-border-style);border-width:1px}.border-\[\#19140035\]{border-color:#19140035}.border-\[\#e3e3e0\]{border-color:#e3e3e0}.border-black{border-color:var(--color-black)}.border-transparent{border-color:#0000}.bg-\[\#1b1b18\]{background-color:#1b1b18}.bg-\[\#FDFDFC\]{background-color:#fdfdfc}.bg-\[\#dbdbd7\]{background-color:#dbdbd7}.bg-\[\#fff2f2\]{background-color:#fff2f2}.bg-white{background-color:var(--color-white)}.p-6{padding:calc(var(--spacing)*6)}.px-5{padding-inline:calc(var(--spacing)*5)}.py-1{padding-block:calc(var(--spacing)*1)}.py-1\.5{padding-block:calc(var(--spacing)*1.5)}.py-2{padding-block:calc(var(--spacing)*2)}.pb-12{padding-bottom:calc(var(--spacing)*12)}.text-sm{font-size:var(--text-sm);line-height:var(--tw-leading,var(--text-sm--line-height))}.text-\[13px\]{font-size:13px}.leading-\[20px\]{--tw-leading:20px;line-height:20px}.leading-normal{--tw-leading:var(--leading-normal);line-height:var(--leading-normal)}.font-medium{--tw-font-weight:var(--font-weight-medium);font-weight:var(--font-weight-medium)}.text-\[\#1b1b18\]{color:#1b1b18}.text-\[\#706f6c\]{color:#706f6c}.text-\[\#F53003\],.text-\[\#f53003\]{color:#f53003}.text-white{color:var(--color-white)}.underline{text-decoration-line:underline}.underline-offset-4{text-underline-offset:4px}.opacity-100{opacity:1}.shadow-\[0px_0px_1px_0px_rgba\(0\,0\,0\,0\.03\)\,0px_1px_2px_0px_rgba\(0\,0\,0\,0\.06\)\]{--tw-shadow:0px 0px 1px 0px var(--tw-shadow-color,#00000008),0px 1px 2px 0px var(--tw-shadow-color,#0000000f);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.shadow-\[inset_0px_0px_0px_1px_rgba\(26\,26\,0\,0\.16\)\]{--tw-shadow:inset 0px 0px 0px 1px var(--tw-shadow-color,#1a1a0029);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.\!filter{filter:var(--tw-blur,)var(--tw-brightness,)var(--tw-contrast,)var(--tw-grayscale,)var(--tw-hue-rotate,)var(--tw-invert,)var(--tw-saturate,)var(--tw-sepia,)var(--tw-drop-shadow,)!important}.filter{filter:var(--tw-blur,)var(--tw-brightness,)var(--tw-contrast,)var(--tw-grayscale,)var(--tw-hue-rotate,)var(--tw-invert,)var(--tw-saturate,)var(--tw-sepia,)var(--tw-drop-shadow,)}.transition-all{transition-property:all;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function));transition-duration:var(--tw-duration,var(--default-transition-duration))}.transition-opacity{transition-property:opacity;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function));transition-duration:var(--tw-duration,var(--default-transition-duration))}.delay-300{transition-delay:.3s}.duration-750{--tw-duration:.75s;transition-duration:.75s}.not-has-\[nav\]\:hidden:not(:has(:is(nav))){display:none}.before\:absolute:before{content:var(--tw-content);position:absolute}.before\:top-0:before{content:var(--tw-content);top:calc(var(--spacing)*0)}.before\:top-1\/2:before{content:var(--tw-content);top:50%}.before\:bottom-0:before{content:var(--tw-content);bottom:calc(var(--spacing)*0)}.before\:bottom-1\/2:before{content:var(--tw-content);bottom:50%}.before\:left-\[0\.4rem\]:before{content:var(--tw-content);left:.4rem}.before\:border-l:before{content:var(--tw-content);border-left-style:var(--tw-border-style);border-left-width:1px}.before\:border-\[\#e3e3e0\]:before{content:var(--tw-content);border-color:#e3e3e0}@media (hover:hover){.hover\:border-\[\#1915014a\]:hover{border-color:#1915014a}.hover\:border-\[\#19140035\]:hover{border-color:#19140035}.hover\:border-black:hover{border-color:var(--color-black)}.hover\:bg-black:hover{background-color:var(--color-black)}}@media (width>=64rem){.lg\:-mt-\[6\.6rem\]{margin-top:-6.6rem}.lg\:mb-0{margin-bottom:calc(var(--spacing)*0)}.lg\:mb-6{margin-bottom:calc(var(--spacing)*6)}.lg\:-ml-px{margin-left:-1px}.lg\:ml-0{margin-left:calc(var(--spacing)*0)}.lg\:block{display:block}.lg\:aspect-auto{aspect-ratio:auto}.lg\:w-\[438px\]{width:438px}.lg\:max-w-4xl{max-width:var(--container-4xl)}.lg\:grow{flex-grow:1}.lg\:flex-row{flex-direction:row}.lg\:justify-center{justify-content:center}.lg\:rounded-t-none{border-top-left-radius:0;border-top-right-radius:0}.lg\:rounded-tl-lg{border-top-left-radius:var(--radius-lg)}.lg\:rounded-r-lg{border-top-right-radius:var(--radius-lg);border-bottom-right-radius:var(--radius-lg)}.lg\:rounded-br-none{border-bottom-right-radius:0}.lg\:p-8{padding:calc(var(--spacing)*8)}.lg\:p-20{padding:calc(var(--spacing)*20)}}@media (prefers-color-scheme:dark){.dark\:block{display:block}.dark\:hidden{display:none}.dark\:border-\[\#3E3E3A\]{border-color:#3e3e3a}.dark\:border-\[\#eeeeec\]{border-color:#eeeeec}.dark\:bg-\[\#0a0a0a\]{background-color:#0a0a0a}.dark\:bg-\[\#1D0002\]{background-color:#1d0002}.dark\:bg-\[\#3E3E3A\]{background-color:#3e3e3a}.dark\:bg-\[\#161615\]{background-color:#161615}.dark\:bg-\[\#eeeeec\]{background-color:#eeeeec}.dark\:text-\[\#1C1C1A\]{color:#1c1c1a}.dark\:text-\[\#A1A09A\]{color:#a1a09a}.dark\:text-\[\#EDEDEC\]{color:#ededec}.dark\:text-\[\#F61500\]{color:#f61500}.dark\:text-\[\#FF4433\]{color:#f43}.dark\:shadow-\[inset_0px_0px_0px_1px_\#fffaed2d\]{--tw-shadow:inset 0px 0px 0px 1px var(--tw-shadow-color,#fffaed2d);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.dark\:before\:border-\[\#3E3E3A\]:before{content:var(--tw-content);border-color:#3e3e3a}@media (hover:hover){.dark\:hover\:border-\[\#3E3E3A\]:hover{border-color:#3e3e3a}.dark\:hover\:border-\[\#62605b\]:hover{border-color:#62605b}.dark\:hover\:border-white:hover{border-color:var(--color-white)}.dark\:hover\:bg-white:hover{background-color:var(--color-white)}}}@starting-style{.starting\:translate-y-4{--tw-translate-y:calc(var(--spacing)*4);translate:var(--tw-translate-x)var(--tw-translate-y)}}@starting-style{.starting\:translate-y-6{--tw-translate-y:calc(var(--spacing)*6);translate:var(--tw-translate-x)var(--tw-translate-y)}}@starting-style{.starting\:opacity-0{opacity:0}}}@keyframes spin{to{transform:rotate(360deg)}}@keyframes ping{75%,to{opacity:0;transform:scale(2)}}@keyframes pulse{50%{opacity:.5}}@keyframes bounce{0%,to{animation-timing-function:cubic-bezier(.8,0,1,1);transform:translateY(-25%)}50%{animation-timing-function:cubic-bezier(0,0,.2,1);transform:none}}@property --tw-translate-x{syntax:"*";inherits:false;initial-value:0}@property --tw-translate-y{syntax:"*";inherits:false;initial-value:0}@property --tw-translate-z{syntax:"*";inherits:false;initial-value:0}@property --tw-rotate-x{syntax:"*";inherits:false;initial-value:rotateX(0)}@property --tw-rotate-y{syntax:"*";inherits:false;initial-value:rotateY(0)}@property --tw-rotate-z{syntax:"*";inherits:false;initial-value:rotateZ(0)}@property --tw-skew-x{syntax:"*";inherits:false;initial-value:skewX(0)}@property --tw-skew-y{syntax:"*";inherits:false;initial-value:skewY(0)}@property --tw-space-x-reverse{syntax:"*";inherits:false;initial-value:0}@property --tw-border-style{syntax:"*";inherits:false;initial-value:solid}@property --tw-leading{syntax:"*";inherits:false}@property --tw-font-weight{syntax:"*";inherits:false}@property --tw-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-shadow-color{syntax:"*";inherits:false}@property --tw-inset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-shadow-color{syntax:"*";inherits:false}@property --tw-ring-color{syntax:"*";inherits:false}@property --tw-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-ring-color{syntax:"*";inherits:false}@property --tw-inset-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-ring-inset{syntax:"*";inherits:false}@property --tw-ring-offset-width{syntax:"<length>";inherits:false;initial-value:0}@property --tw-ring-offset-color{syntax:"*";inherits:false;initial-value:#fff}@property --tw-ring-offset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-blur{syntax:"*";inherits:false}@property --tw-brightness{syntax:"*";inherits:false}@property --tw-contrast{syntax:"*";inherits:false}@property --tw-grayscale{syntax:"*";inherits:false}@property --tw-hue-rotate{syntax:"*";inherits:false}@property --tw-invert{syntax:"*";inherits:false}@property --tw-opacity{syntax:"*";inherits:false}@property --tw-saturate{syntax:"*";inherits:false}@property --tw-sepia{syntax:"*";inherits:false}@property --tw-drop-shadow{syntax:"*";inherits:false}@property --tw-duration{syntax:"*";inherits:false}@property --tw-content{syntax:"*";inherits:false;initial-value:""}
    </style>
@endif
```

# Tailwind
Antes configurabase nun ficheiro de configuración, ahora faise directamente en 
app.css

```css
@import 'tailwindcss';

@tailwind base;
@tailwind components;
@tailwind utilities;

@layer utilities{
    .proba{ /*poñendo a clase proba ponse a letra rosa */
        color: pink;
    }
}
```



