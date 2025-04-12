<details>
  <summary>Rutas e apache</summary>

# Rutas e apache
De normal, apache busca archivos(`index.php`) ou directorios(`/www/views`) e se non existen
devolve un error.

Se queremos por exemplo facer routing en php, hai que configurar apache para que
se unha ruta non é un archivo nin directorio, redireccione siempre a `index.php`,
que é onde teremos o router.

Configurandoo xa poderiamos usar rutas como `http://localhost:8080/dashboard`

Para pillar solo a url, sin parametros como `?name=jorge` usase `parse_url`:
````php
$uri = $_SERVER["REQUEST_URI"] //pillamos a url da request enteira
$urlSeparada = parse_url($uri); //ahora teremos a url separada dos parametros

$urlSinParametros = $urlSeparada["path"]
$parametros = $urlSeparada["query"]
````
</details>



<details>
  <summary>Depuracion</summary>
# Depuracion
Para imprimir unha variable usamos `var_dump()`, e para parar a execución do codigo nese punto `die()`.

Asi que para que quede bonito, podemos facer unha funcion que xunte todo esto e o formatee con `<pre>`

````php
//dump and die
function dd($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    die();
}
````
</details>

<details>
  <summary>Require</summary>

# Require
Para incluir archivos dentro de outros usase `require`:
````php
case "/dashboard":
        require "controllers/dashboard.php";
        break;
default:
    require "controllers/notFound.php";
````
⚠️PERO cuidado, porque o require é interpretado relativamente dende o ficheiro
que inicia o request, por ejemplo se o request inicia en index.php, ainda que
fagamos un require en notFound.php a ruta relativa vai ser a de index.php.

Para usar correctamente dir poden usarse tres maneiras:
* Como todas as request unha vez teñamos o router van ir dirixidas ao index, todos os require
van ser interpretados dende o directorio do index.php, así que simplemente poñemos todas as rutas
como se foran dende o `index.php`
* usaremos `__DIR__`, que basicamente devolve a ruta do archivo actual.
* ✔️ RECOMENDADA: no index declarar unha constante `BASE_PATH` que indique a raiz do proxecto
, e facer todos os require concatenando esa raíz.

## Ejemplos
Estructura de directorios dos ejemplos:
* index.php
* controllers
    * notFound.php
* views
    * notFound.view.php
### Ejemplo que non vai funcionar ❌:
`index.php`
````php
require "controllers/notFound.php";
````
`notFound.php`
````php
require "../views/notFound.view.php";
````
Esto non vai funcionar, xa que o require dende `notFound.php`
vai ser interpretada como se a chamara `index.php`.

chati: PHP resolves relative paths based on the file that initiates the request, not the file in which the require is written.

So, ../views/notFound.view.php is interpreted relative to index.php, not notFound.php.

### Ejemplo CORRECTO con rutas dende o index.php
`index.php`
````php
require "controllers/notFound.php";
````
`notFound.php`
````php
require "views/notFound.view.php";
````
### Ejemplo CORRECTO usando __DIR__
`index.php`
````php
require "controllers/notFound.php";
````
`notFound.php`
````php
require __DIR__ . "/../views/notFound.view.php";
````
Esto vai funcionar, xa que usamos `__DIR__`, polo que a ruta
será relativa ao ficheiro que esta facendo o require, `notFound.php` neste caso
, e non a `index.php`

### Ejemplo correcto usando `BASE_PATH` ✔️
Este exemplo mezcla a primeira opción coa segunda, xa que no `index.php` indicaremos
a raiz. Sobretodo porque o index non suele estar na raiz do proxecto, senon
na carpeta public.

index.php:
````php
<?php

//index esta en www/public, pois indicamos que a raiz é www
const BASE_PATH = __DIR__."/../"
````
Ahora simplemente faremos todos os require engadindo esto ao principio, 
podemos facer unha función para que sexa mais facil:
````php
function basePath(string $path){
    return BASE_PATH.$path;
}

require basePath("Database2.php"); // www/Database2.php
````

## Require para obter variables
Tamen se pode usar require para por ejemplo, inicializar unha variable, se o arquivo
que incluimos fai un `return`

Por exemplo, podemos inicializar unha variable de configuración:

`config.php`
````php
<?php

return $config = [
    "dbType" => "mysql",
    "host" => "localhost",
    "port" => 3306,
    "dbName" => "proyecto2",
    "charset" => "utf8mb4"
];
````
`index.php`
````php
$config = require "config.php";
````

</details>


<details>
  <summary>Namespacing</summary>

# Namespacing
Basicamente sirven para diferenciar clases a nivel de codigo, son como carpetas
virtuales. Se cambiamos o namespace dunha clase, cambiará o seu `FQCN` (fully qualified class name),
é dicir, o nome completo de esa clase. Por ejemplo se a clase User non lle indicamos un namespace,
o seu fqcn vai ser User, pero se indicamos que esta no namespace Core, enton vai ser Core\User.
Esto é mui util porque como diferencia clases ainda que se chamen igual, e ademais deberían
coincidir coa estructura de directorios, despois podemos usar autoloading a partir do fqcn.

❗ Os namespaces sempre deberían equivaler a estructura de directorios, é un estandar
e fai todo muito mais facil e cohesivo.

Por ejemplo, poñamonos que temos duas clases User e queremos usalas no mismo arquivo.
A hora de usar a clase, php non vai saber cal das duas usar, por eso hai que
usar namespaces:

````php
require 'Models/User.php';
require 'Controllers/User.php';

use App\Models\User as ModelUser;
use App\Controllers\User as ControllerUser;

$modelUser = new ModelUser();
$modelUser->sayHi(); // outputs: User model

$controllerUser = new ControllerUser();
$controllerUser->sayHi(); // outputs: User controller

````

Clase `Models/User.php`:
````php
<?php

namespace App\Models;
...
````

Clase `Controllers/User.php`:
````php
<?php

namespace App\Controllers
....
````

Como se ve, basicamente é unha forma de darlle un 'alias' a esa clase para
poder diferenciala de outra.

## Con spl_autoload_register
`spl_autoload_register` ejecutase sempre que usemos un obxeto e non o teñamos importado.
Como parametro pasaselle unha función anonima que recibe o fqcn como parametro, e que indicará
como requerir esa clase.

Esto combinado cos namespaces esta dpm, porque
podemos facer que cargue as clases solo usando `use`.

Esto é basicamente o que usa composer para facer autoload das clases
que se usan, así que esta guay saber como funciona por detrás.

### Ej
Ej. Temos unha clase `Database2` que esta no namespace Core, e por tanto
debería estar, dende o root do proxecto, en Core/Database2.php.

Database2.php:
````php
<?php

namespace Core; //indicamos o namespace

class Database2
{
````
Index.php, onde usamos a funcion spl_autoload_register para requerir esa clase a partir
do fqcn:
````php
spl_autoload_register(function($class){
    //sustituimos os \ por o separador de directorio
    $requireUrl = str_replace("\\", DIRECTORY_SEPARATOR, $class); // Core\User a Core/User
    require basePath("{$requireUrl}.php"); // require BASE_PATH."Core/User.php";
});
````
</details>

---
<details>
  <summary>Variables globales</summary>

# Variables globales
Completar con $_SERVER, $_POST, $_GET, $_FILES....


</details>


<details>
  <summary>Variables</summary>

# Variables
````php
<?php

$palabra = "ola";
$numero = 56;
$arrayNormal = [45, "ola que tal"];
    //añadir ao array
    $arrayNormal[] = 78;
    //pillar o segundo valor
    $arrayNormal[1];
$diccionario = [
    [
        "nombre" => "Pepe",
        "anos" => 67
    ],
    [
        "nombre" => "Pepe",
        "anos" => 67
    ]
]
    //pillar o nombre do primeiro
    $diccionario[0]["nombre"]
    //recorrelo
    foreach (String $diccionario as $item){
        $item["nombre"];
    }
````

## Funcions arrays
### array_filter
Pasaselle un `array` e unha `funcion anonima` para filtrar, por cada elementos do array
ejecutará a función, a cal ten q devolver true ou false:
````php
$filtrados = array_filter($books, function ($book){
    return $book["ano"] > 45;
});
````
### extract
Pilla un diccionario e crea tantas variables como claves teña
o array, poñendolle como nome a clave e como valor o valor.
````php
extract([
    "nome" => "paco",
    "apellido" => "gonzalez"
])
//poderemos acceder a variable $nome
echo($nome)
````
### compact
O contrario que extract, mete todas as variables que lle pases en
un array clave-valor.
````php
$nome = "jorge";
$edad = 12;
$novoArray = compact('nome','edad'); //["nome"=>"jorge","edad"=>12]
````

</details>

<details>
  <summary>Condicionales</summary>

# Condicionales
## If
````php
<?php
$ano = 34;

//compara o valor e o tipo
if ($ano === "34"){
    //NON entra porque non é o mismo tipo
}

if ($ano == "34"){
    //SI entra solo compara o valor
}

if ($ano === 34 && $ano <= 45 || $ano > 70){

}elseif ($ano === 45){

}else{

}

//short if
$ano = (45<98) ? "É menor" : "Maior";

//null coalescing, é null a variable ponse o da dereita
$nome = "Pepe"
$nomeSeExisteSenonNull = $nome ?? "Por defecto"; 
````
## Switch
````php
$dia = "lunes";
switch ($dia){
    case "Lunes":
        echo "Hoxe é lunes";
        break;
    default:
        echo "Non coincidiu con nada";
}
````
## Match
Un switch mais bonito e que se pode usar pa asignación en variables
````php
$fruta = "mazan";
$mensaje = match($fruta){
    "pera" => "son pera",
    "mazan" => "son unha mazan!",
    default => "non son nada:("
}
````

</details>

<details>
  <summary>Funcions</summary>

# Funcions
## Named functions
Daselles un nome e chamaselles por el:
````php
<?php

function filterByAuthor($books, $nombre){
        $filteredBooks = [];
        foreach ($books as $book){
            if ($book["nombre"] === $nombre){
                $filteredBooks[] = $book;
            }
        }
        return $filteredBooks;
    }

$librosFiltrados = filterByAuthor($arrayLibros, "star wars");
````

## Unnamed functions
Podense asignar a unha variable ou usarse directamente:
````php
$filterByAuthor = function ($books, $nombre){
        //filtrase e devolvese algo
    }
//usamola como se fora unha variable
$librosFiltrados = $filterByAuthor($arrayLibros, "star wars");
````

### Pasar e usar unnamed functions
Podense pasar funcions como parametro (suelese chamar a variable `$fn`) para usarse en outro sitio, facendo todo
muito mais flexible:
````php
$filterBy = function ($itemList, $fn){
        $filteredList = [];
        foreach ($itemList as $item){
            if ($fn($item)){ //usa a funcion dos parametros para filtrar
                $filteredList[] = $item;
            }
        }
        return $filteredList;
    };
//pasamos a funcion anonima a outra funcion para que filtre
$filtrados = $filterBy($books, function ($book){
    return $book["ano"] > 45;
})
````

</details>
























