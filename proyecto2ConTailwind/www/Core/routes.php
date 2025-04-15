<?php

use Core\Middleware\Auth;
use Core\Middleware\Guest;

$router->get('/', 'controllers/dashboard.php')->middleware(Auth::class);
$router->get('/dashboard', 'controllers/dashboard.php')->middleware(Auth::class);;
$router->get('/team', 'controllers/team.php')->middleware(Auth::class);
$router->get('/projects', 'controllers/projects.php')->middleware(Auth::class);

$router->get('/notes', 'controllers/notes/index.php')->middleware(Auth::class);
$router->post('/notes', 'controllers/notes/store.php')->middleware(Auth::class);
$router->get('/note', 'controllers/notes/show.php')->middleware(Auth::class);
$router->get('/note/edit', 'controllers/notes/edit.php')->middleware(Auth::class);
$router->patch('/note', 'controllers/notes/update.php')->middleware(Auth::class);
$router->delete('/note', 'controllers/notes/destroy.php')->middleware(Auth::class);
$router->get('/notes/create', 'controllers/notes/create.php')->middleware(Auth::class);

$router->get("/login","controllers/login/index.php")->middleware(Guest::class);

$router->get("/registration", "controllers/registration/create.php")->middleware(Guest::class);;
$router->post("/registration", "controllers/registration/store.php")->middleware(Guest::class);;