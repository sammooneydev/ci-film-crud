<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
    <?= view('templates/header')?>
        <body>
            <div class="main-content">
                <h2>Login:</h2>
                <br>
                <form method='post' action='<?= base_url('login') ?>'>
                    <?= csrf_field() ?>

                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>

                    <button type="submit">Login</button>
                </form>
            </div>
        </body>
    <?= view('templates/footer')?>
</html>