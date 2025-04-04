<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Demo</title>
</head>
<body>
    <ul>
        <?php foreach ($books as $book): ?>
            <li><?= $book["nombre"] ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

