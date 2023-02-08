<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.3/css/bootstrap.min.css') ?>
    <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css') ?>
    <?= $this->Html->css('jquery.datetimepicker.min.css') ?>
    <?= $this->Html->script('jquery.js') ?>
    <?= $this->Html->script('jquery.datetimepicker.full.min.js') ?>
    <script>
        jQuery.datetimepicker.setLocale('ru');
    </script>
    <style>
        header .btn-primary a {
            text-decoration: none;
            color: #fff
        }
    </style>
</head>
<body>

<?= $this->fetch('content') ?>


</body>
</html>
