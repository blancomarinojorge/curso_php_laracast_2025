<?php require "parts/head.view.php"; ?>
<?php require "parts/nav.php"; ?>
<?php require "parts/header.php"; ?>
    <main>
        <form method="post" action="/noteCreation" style="display: flex;flex-direction: column;padding: 40px;gap: 10px">
            <textarea>

            </textarea>
            <button type="submit">Crear</button>
        </form>
    </main>
<?php require "parts/footer.view.php"; ?>