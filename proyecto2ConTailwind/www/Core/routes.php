<?php

use Core\Middleware\Auth;
use Core\Middleware\Guest;

$router->get('/', 'Http/controllers/dashboard.php')->middleware(Auth::class);
$router->get('/dashboard', 'Http/controllers/dashboard.php')->middleware(Auth::class);;
$router->get('/team', 'Http/controllers/team.php')->middleware(Auth::class);
$router->get('/projects', 'Http/controllers/projects.php')->middleware(Auth::class);

$router->get('/notes', 'Http/controllers/notes/index.php')->middleware(Auth::class);
$router->post('/notes', 'Http/controllers/notes/store.php')->middleware(Auth::class);
$router->get('/note', 'Http/controllers/notes/show.php')->middleware(Auth::class);
$router->get('/note/edit', 'Http/controllers/notes/edit.php')->middleware(Auth::class);
$router->patch('/note', 'Http/controllers/notes/update.php')->middleware(Auth::class);
$router->delete('/note', 'Http/controllers/notes/destroy.php')->middleware(Auth::class);
$router->get('/notes/create', 'Http/controllers/notes/create.php')->middleware(Auth::class);

$router->get("/login","Http/controllers/session/create.php")->middleware(Guest::class);
$router->post("/session","Http/controllers/session/store.php")->middleware(Guest::class);
$router->delete("/session","Http/controllers/session/destroy.php")->middleware(Auth::class);

$router->get("/registration", "Http/controllers/registration/create.php")->middleware(Guest::class);;
$router->post("/registration", "Http/controllers/registration/store.php")->middleware(Guest::class);;