<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
    <?= view('templates/header')?>
        <body>
            <div class="main-content">
                <div class="log-content">
                    <h1>xAPI Pre-prepared Statement</h1>

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

                <div class="log-content">
                    <h1>xAPI Statement Builder</h1>

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

                    <form method="post" action="<?= base_url('statement-builder')?>">
                        <?= csrf_field()?>

                        <?php if(!session()->get('username')): ?>
                            <label for="username">Enter username</label>
                            <input name="username" type="text" id="username" required>
                        <?php endif ?>

                        <label for="email">Enter email address</label>
                        <input name="email" type="email" id="email" required>

                        <label for="verb">Select Verb</label>
                        <input name="verb" type="search" id="verb" list="verb-options" required>

                        <datalist id="verb-options">
                            <option value="Viewed"></option>
                            <option value="Accessed"></option>
                            <option value="Completed"></option>
                            <option value="Created"></option>
                            <option value="Found"></option>
                            <option value="Interacted"></option>
                            <option value="Opened"></option>
                            <option value="Started"></option>
                        </datalist>

                        <label for="object">Select Activity Type</label>
                        <input name="object" type="search" id="object" list="object-options" required>

                        <datalist id="object-options">
                            <option value="http://activitystrea.ms/schema/1.0/alert">Application</option>
                            <option value="http://activitystrea.ms/schema/1.0/game">Game</option>
                            <option value="http://activitystrea.ms/schema/1.0/page">Page</option>
                            <option value="http://activitystrea.ms/schema/1.0/service">Service</option>
                            <option value="http://adlnet.gov/expapi/activities/course">Course</option>
                            <option value="http://adlnet.gov/expapi/activities/module">Module</option>
                            <option value="http://id.tincanapi.com/activitytype/survey">Survey</option>
                            <option value="https://www.opigno.org/en/tincan_registry/activity_type/certificate">Certificate</option>
                        </datalist>

                        <label for="activity-name">Enter name of activity</label>
                        <input name="activity-name" type="text" id="activity-name" required>

                        <button type="submit">Send Statement</button>
                    </form>
                </div>
            </div>
        </body>
    <?= view('templates/footer')?>
</html>