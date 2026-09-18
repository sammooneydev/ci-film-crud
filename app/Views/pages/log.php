<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="/style.css">
</head>
<?= view('templates/header') ?>
<body>
    <div class="main-content">
        <div class="log-content">
            <h1>Log a film</h1>

            <?php if (session()->getFlashdata('error')): ?>

                <p class="error">
                    <?= esc(session()->getFlashdata('error')) ?>
                </p>

            <?php endif; ?>


            <form action="/log/create" method="post">

                <?= csrf_field() ?>

                <label for="film_id">Film</label>

                <select name="film_id" id="film_id" required>

                    <option value="">Select a film</option>

                    <?php foreach ($films as $film): ?>
                        <option value="<?= esc($film['film_id']) ?>"
                        <?= old('film_id') == $film['film_id'] ? 'selected' : '' ?>>
                            <?= esc($film['film_name']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>

                <label for="score">Score</label>

                <input type="number" name="score" id="score" min="0" max="10" step="0.1" value="<?= old('score') ?>">

                <label for="entry_text">Review</label>

                <textarea name="entry_text" id="entry_text" rows="8"><?= old('entry_text') ?></textarea>

                <button type="submit">
                    Log film
                </button>
            </form>
        </div>
    </div>
</body>
<?= view('templates/footer') ?>
</html>