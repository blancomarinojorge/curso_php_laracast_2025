<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Demo</title>
</head>
<body>
<?php
    $books = [
        [
            "nombre" => "principito",
            "ano" => 45
        ],
        [
            "nombre" => "1885",
            "ano" => 23
        ],
        [
            "nombre" => "star wars",
            "ano" => 45
        ]
    ];

    $filterBy = function ($itemList, $fn){
        $filteredList = [];
        foreach ($itemList as $item){
            if ($fn($item)){
                $filteredList[] = $item;
            }
        }
        return $filteredList;
    };
    $filtrados = $filterBy($books, function ($book){
        return $book["ano"] <= 45;
    });

    $filtrados = array_filter($books, function ($book){
        return $book["ano"] === 45;
    })
?>

<ul>
    <?php foreach ($books as $book): ?>
        <li><a href="<?=$book["ano"]?>"><?= $book["nombre"] ?></a></li>
        <?php if($book["ano"] === "45"): ?>
            <b>la virgen</b>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

    <p>
        <?php foreach ($filtrados as $book): ?>
            <?= $book["nombre"] ?> => <?= $book["ano"] ?><br>
        <?php endforeach; ?>
    </p>
</body>
</html>