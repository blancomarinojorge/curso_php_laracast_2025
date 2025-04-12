<?php

$router->get('/', 'controllers/dashboard.php');
$router->get('/dashboard', 'controllers/dashboard.php');
$router->get('/team', 'controllers/team.php');
$router->get('/projects', 'controllers/projects.php');

$router->get('/notes', 'controllers/notes/index.php');
$router->post('/notes', 'controllers/notes/store.php');
$router->get('/note', 'controllers/notes/show.php');
$router->delete('/note', 'controllers/notes/destroy.php');
$router->get('/notes/create', 'controllers/notes/create.php');