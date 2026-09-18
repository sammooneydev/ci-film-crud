<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
    <?= view('templates/header')?>
        <body>
            <div class="main-content">
                <div class="profile">

                    <h1>Profile</h1>

                    <p>
                        Welcome,
                        <strong><?= esc(session()->get('username'))?></strong>
                    </p>

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

                    <form action="profile/update" method="post">
                        <?= csrf_field()?>

                        <h2>Edit profile</h2>

                        <label for="username">Username</label>

                        <input type="text" id="username" name="username" value="<?= esc(session()->get('username'))?>" required maxlength="200">

                        <h2>Change password</h2>

                        <label for="current_password">Current password</label>
                        <input type="password" id="current_password" name="current_password">

                        <label for="new_password">New password</label>
                        <input type="password" id="new_password" name="new_password">

                        <button type="submit">Save changes</button>
                    </form>

                    <div class="delete-account">

                        <h2>Delete account</h2>

                        <p>This will permanently delete your account.</p>

                        <form action="profile/delete" method="post" onsubmit="return confirm('Are you sure that you want to permanently delete your account? This cannot be undone.');">
                            <?= csrf_field()?>

                            <button type="submit">
                                Delete account
                            </button>

                        </form>
                    </div>
                </div>

                <div class="reviews">

                    <h2>Your reviews</h2>

                    <?php if (empty($reviews)): ?>

                        <p>You haven't logged any films yet.</p>

                    <?php else: ?>

                        <div class="review-list">

                            <?php foreach ($reviews as $review): ?>

                                <div class="review-card">

                                    <h3><?= esc($review['film_name']) ?></h3>

                                    <?php if ($review['score'] !== null): ?>

                                        <p class="review-score"><?= esc($review['score']) ?>/10</p>

                                    <?php endif; ?>

                                    <p class="review-date"><?= date('d/m/Y H:i', strtotime($review['date_posted'])) ?></p>

                                    <p class="review-text"><?= nl2br(esc($review['entry_text'])) ?></p>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </body>
    <?= view('templates/footer')?>
</html>