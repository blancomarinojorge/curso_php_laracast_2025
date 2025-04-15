<?php require basePath("views/parts/head.view.php"); ?>
<?php require basePath("views/parts/nav.php"); ?>
<?php require basePath("views/parts/header.php"); ?>
    <main>
        <form method="post" action="/note" style="display: flex;flex-direction: column;padding: 40px;gap: 10px">
            <input type="hidden" name="__request_method" value="patch">
            <input type="hidden" name="id" value="<?= $note["id"] ?>">
            <textarea name="noteBody">
                <?= $body ?? '' ?>
            </textarea>
            <?php if (isset($errors["body"])): ?>
                <div style="color: red;font-size: 12px">
                    <?=  $errors["body"] ?>
                </div>
            <?php endif; ?>
            <a href="/note?id=<?= $note["id"] ?>">Cancelar</a>
            <button type="submit">Modificar</button>
        </form>
    </main>
<?php require basePath("views/parts/footer.view.php"); ?>