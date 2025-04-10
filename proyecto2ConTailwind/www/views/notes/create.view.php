<?php require "views/parts/head.view.php"; ?>
<?php require "views/parts/nav.php"; ?>
<?php require "views/parts/header.php"; ?>
    <main>
        <form method="post" action="/createNote" style="display: flex;flex-direction: column;padding: 40px;gap: 10px">
            <textarea name="noteBody">
                <?= $body ?? '' ?>
            </textarea>
            <?php if (isset($errors["body"])): ?>
                <div style="color: red;font-size: 12px">
                    <?=  $errors["body"] ?>
                </div>
            <?php endif; ?>
            <button type="submit">Crear</button>
        </form>
    </main>
<?php require "views/parts/footer.view.php"; ?>