<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
    <?= view('templates/header')?>
        <body>
            <div class="main-content">

                <div class="profile">
                        <?php if(session()->getFlashdata('success')):?>
                            <p class="success">
                                <?= esc(session()->getFlashdata('success'))?>
                            </p>
                        <?php endif;?>

                        <?php if(session()->getFlashdata('error')):?>
                            <p class="error">
                                <?= esc(session()->getFlashdata('error'))?>
                            </p>
                        <?php endif;?>

                        <form class="login" method="post" action="<?= base_url('admin/create-film') ?>">
                        <h2>Add Film to Database</h2>
                        <?= csrf_field() ?>

                        <label for="film_name">Film Name</label>
                        <input type="text" name="film_name" id="film_name" required>

                        <br><br>

                        <label for="film_desc">Description</label>
                        <textarea name="film_desc" id="film_desc"></textarea>

                        <br><br>

                        <label for="director_name">Director</label>
                        <input type="text" name="director_name" id="director_name" required>

                        <br><br>

                        <button type="submit">Add Film</button>
                    </form>
                </div>
            </div>
        </body>
    <?= view('templates/footer')?>
</html>