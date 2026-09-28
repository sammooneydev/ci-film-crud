<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
    <?= view('templates/header')?>
        <body>
            <div class="main-content">
                <div class="api-test-content">
                    <h1>xAPI Testing Stuff</h1>

                    <?php if (session()->getFlashdata('success')): ?>
                    <p class="success">
                        <?= esc(session()->getFlashdata('success')) ?>
                    </p>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <p class="error">
                            <?= esc(session()->getFlashdata('error')) ?>
                        </p>
                    <?php endif; ?>

                    <form action="<?= base_url('api-test/prepared') ?>" method="post">
                        <?= csrf_field() ?>

                        <button type="submit">Send Pre-prepared xAPI Statement</button>

                    </form>
                </div>
            </div>
        </body>
    <?= view('templates/footer')?>
</html>