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

            <div class="uk-navbar-right">
                <?php /*Menu block*/;?>
                <!-- NAUJAI IRASYTAS - PRADZIA -->
                <div class>
                    <a href="<?= BASE_URL; ?>" class="uk-navbar-item uk-logo"  uk-navbar="mode: click">Persons</a>
                </div>
                <div class>
                    <a href="<?= BASE_URL; ?>" class="uk-navbar-item uk-logo"  uk-navbar="mode: click">Projects</a>
                </div>
                <div class>
                    <a href="<?= BASE_URL; ?>" class="uk-navbar-item uk-logo"  uk-navbar="mode: click">Departments</a>
                </div>
                <!-- <nav class="uk-navbar-container uk-margin uk-light uk-background-secondary uk-position-center-right" uk-navbar="mode: click">

                    <div class="uk-navbar-left uk-light uk-background-secondary">

                        <ul class="uk-navbar-nav uk-light uk-background-secondary">
                            <li class="uk-active uk-light uk-background-secondary"><a href="#">Persons</a></li>
                            <li class="uk-light uk-background-secondary"><a href="#">Projects</a></li>
                            <li class="uk-light uk-background-secondary"><a href="#">Departments</a></li>
                            <li><a href="#">Projects</a>
                                <div class="uk-navbar-dropdown uk-light uk-background-secondary">
                                    <ul class="uk-nav uk-navbar-dropdown-nav uk-light uk-background-secondary">
                                        <li class="uk-active uk-light uk-background-secondary"><a href="#">Active</a></li>
                                        <li><a href="#">Item</a></li>
                                        <li><a href="#">Item</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li><a href="#">Item</a></li>
                        </ul>

                    </div>
                </nav> -->
                <!-- NAUJAI IRASYTAS - PABAIGA -->

            </div>

            <!-- <div class="uk-navbar-right">

                <php /*Language switcher, login button*/;?>

            </div> -->
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

