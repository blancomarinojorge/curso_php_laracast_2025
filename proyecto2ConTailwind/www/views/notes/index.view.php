<?php require "views/parts/head.view.php"; ?>
<?php require "views/parts/nav.php"; ?>
<?php require "views/parts/header.php"; ?>
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <ul>
                <?php foreach ($notes as $note): ?>
                    <li>
                        <a href="/note?id=<?= $note["id"] ?>">
                        <?= $note["body"] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <a href="/createNote" style="padding: 10px;background-color: aquamarine;display: flex;justify-content: center;align-items: center;max-width: 200px">
            Crear nota
        </a>

    </main>
<?php require "views/parts/footer.view.php"; ?>