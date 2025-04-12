<?php require basePath("views/parts/head.view.php"); ?>
<?php require basePath("views/parts/nav.php"); ?>
<?php require basePath("views/parts/header.php"); ?>
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <ul>
                    <li>
                        <a href="/note?id=<?= $note["id"] ?>">
                        <?= $note["body"] ?>
                        </a>
                    </li>
            </ul>

            <form action="" method="post">
                <input type="hidden" name="__request_method" value="DELETE">
                <input type="hidden" name="noteId" value="<?= $note["id"] ?>">
                <button style="color: red">Delete</button>
            </form>
        </div>
    </main>
<?php require basePath("views/parts/footer.view.php"); ?>