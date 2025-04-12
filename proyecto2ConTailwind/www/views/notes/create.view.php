<?php require basePath("views/parts/head.view.php"); ?>
<?php require basePath("views/parts/nav.php"); ?>
<?php require basePath("views/parts/header.php"); ?>
    <main>
        <form method="post" action="/notes" style="display: flex;flex-direction: column;padding: 40px;gap: 10px">
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
<?php require basePath("views/parts/footer.view.php"); ?>