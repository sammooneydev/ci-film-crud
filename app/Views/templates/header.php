<?php
$logged_in = session()->get('logged_in');

if (empty($logged_in) || !$logged_in) {
    $logged_in = false;
}
else {
    $username = session()->get('username');
    $is_admin = session()->get('is_admin');
}
?>

<header>
    <div class="header-container">

        <div class="navigation">
            <ul class="nav-list">
                <li>
                    <a href="<?= base_url('/') ?>">
                        Home
                    </a>
                </li>
                <?php if(!$logged_in):?>
                <li>
                    <a href="<?= base_url('login')?>">
                        Login
                    </a>
                </li>
                <?php elseif($logged_in):?>
                    <li>
                        <a href="<?= base_url('profile')?>">
                            <?php echo($username);?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('logout')?>">
                            Logout
                        </a>
                    </li>
                <?php endif;?>
                
            </ul>
        </div>

    </div>
</header>