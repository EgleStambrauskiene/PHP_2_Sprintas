<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>The Second Sprint at BT</title>
<link rel="stylesheet" href="<?= BASE_URL . '/public/css/uikit/uikit.min.css';?>">
<!--<link rel="stylesheet" href="">-->
</head>
<body>

<nav class="uk-navbar-container uk-background-secondary uk-light uk-margin-bottom uk-navbar-transparent" uk-navbar>
<div class="uk-navbar-left">
<?php /*Site title*/;?>
<a href="<?= BASE_URL; ?>" class="uk-navbar-item uk-logo">The Second Sprint</a>
</div>

<div class="uk-navbar-center">

<?php /*Menu block*/;?>

</div>

<div class="uk-navbar-right">

<?php /*Lang switcher, login button*/;?>

</div>
</nav>

<?php if (isset($_SESSION['messages'])): ?>
<section id="messages-section">
<div class="uk-container">
<?php foreach (['primary', 'success', 'warning', 'danger'] as $alertClass):?>
<?php if (isset($_SESSION['messages'][$alertClass])):?>
<div class="uk-alert-<?= $alertClass;?>" uk-alert>
<a class="uk-alert-close" uk-close></a>
<ul class="uk-list">
<?php foreach ($_SESSION['messages'][$alertClass] as $alert): ?>
<li><?= $alert; ?></li>
<?php endforeach;?>
</ul>
</div>
</div>
<?php endif;?>
<?php endforeach;?>
<?php unset($_SESSION['messages']);?>
</section>
<?php endif;?>

<?php if (isset($form)): ?>
<section id="form-section">
<div class="uk-container">
<?= $form;?>
</div>
</section>
<?php endif;?>

<?php if (isset($list)): ?>
<section id="list-section">
<div class="uk-container">
<?= $list; ?>
</div>
</section>
<?php endif;?>


<?php if (isset($dashboard)): ?>
<section id="dashboard-section">
<div class="uk-container">

</div>
</section>
<?php endif;?>


<?php if (isset($status)): ?>
<section id="status-section">
<div class="uk-container">
<?= $status;?>
</div>
</section>
<?php endif;?>


<script defer type="text/javascript" src="<?= BASE_URL . '/public/js/uikit/uikit.min.js';?>"></script>
<script defer type="text/javascript" src="<?= BASE_URL . '/public/js/uikit/uikit-icons.min.js';?>"></script>
</body>
</html>

