<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
    <?= view('templates/header')?>
        <body>
            <div class="main-content">

                <form class="login" method='post' action='<?= base_url('login') ?>'>
                    <h2>Login</h2>
                    <?= csrf_field() ?>

                    <label for="username">Username or email</label>
                    <input type="text" name="login" id="login" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>

                    <button type="submit">Login</button>
                </form>

                <form class="login" method="post" action="<?= base_url('create-account')?>">
                    <h2>Create Account</h2>
                    <?= csrf_field() ?>

                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>

                    <button type="submit">Create Account</button>
                </form>
            </div>
        </body>
    <?= view('templates/footer')?>
</html>