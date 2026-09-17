<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
    <?= view('templates/header')?>
        <body>
            <div class="main-content">
                <p>welcome <?php echo('' . session()->get('username'))?>, this is the profile page</p>
            </div>
        </body>
    <?= view('templates/footer')?>
</html>