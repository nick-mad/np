<!DOCTYPE html>
<html lang="ru">
<head>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>test np</title>
    <link rel="stylesheet" href="/assets/main.css">
</head>
<body>
<form action="/" method="post" enctype="multipart/form-data">
    <textarea name="date" placeholder="Y/m/d-m.d.Y"
              class="textarea"><?= isset($date) ? htmlspecialchars($date) : '' ?></textarea>
    <button type="submit" class="btn">POST</button>
    <br>
    <button type="button" class="btn" id="ajax">AJAX</button>
</form>
<div id="response">
    <?php if (!empty($error)) : ?>
        <p class="error"><b>Ошибка!</b> <?= $error ?></p>
    <?php endif; ?>
    <?php if (!empty($message)) : ?>
        <p class="success"><?= $message ?></p>
    <?php endif; ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="/assets/main.js"></script>
</body>
</html>