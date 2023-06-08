<h1>About Page</h1>
<a href="<?= url() ?>">Go Back</a>

<hr>

<ul>
    <li>Name:
        <?php echo $name ?>
    </li>
    <li>Email:
        <a href="mailto:<?php echo $email ?>">
            <?php echo $email ?></a>
    </li>
    <li>Github:
        <a href="<?= $github ?>">
            <?= $github ?>
        </a>
    </li>
</ul>