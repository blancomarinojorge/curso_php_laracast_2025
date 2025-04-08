<?php require "parts/head.view.php"; ?>
<?php require "parts/nav.php"; ?>
<?php require "parts/header.php"; ?>
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <ul>
                    <li>
                        <a href="/note?id=<?= $note["id"] ?>">
                        <?= $note["body"] ?>
                        </a>
                    </li>
            </ul>
        </div>
    </main>
<?php require "parts/footer.view.php"; ?>