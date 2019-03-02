<h1><?= __('Departments'); ?></h1>

<div class="uk-margin-bottom">
    <a href="<?= BASE_URL . '/departments/add';?>" class="uk-icon-link uk-margin-right-small" uk-icon="icon: plus; ratio: 1.25"></a>
    <span><?= __('Add record');?></span>
</div>

<?php if (!empty($departments)):?>
    <div class="uk-margin-bottom">
        <a href="<?= BASE_URL . '/departments/list';?>" class="uk-icon-link uk-margin-right-small" uk-icon="icon: list; ratio: 1.25"></a>
        <span><?= __('List');?></span>
    </div>

    <div class="uk-margin-bottom">
        <a href="#" uk-toggle="target: #trash-confirm" class="uk-icon-link uk-margin-right-small" uk-icon="icon: trash; ratio: 1.25"></a>
        <span><?= __('Trash selected');?></span>
    </div>

    <?php if (isset($_SESSION['crud'])): ?>
        <?php foreach (['primary', 'success', 'warning', 'danger'] as $alertClass):?>
            <?php if (isset($_SESSION['crud'][$alertClass])):?>
                <div class="uk-alert-<?= $alertClass;?>" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <ul class="uk-list">
                        <?php foreach ($_SESSION['crud'][$alertClass] as $alert): ?>
                            <li><?= $alert; ?></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            <?php endif;?>
        <?php endforeach;?>
        <?php unset($_SESSION['crud']);?>
    <?php endif;?>

    <form method="POST" action="<?= BASE_URL . '/departments/trash';?>" id="form-trash">
        <div class="uk-child-width-1-1@s uk-child-width-1-2@m uk-child-width-1-2@l" uk-grid="masonry: true" uk-sortable>
            <?php foreach ($departments as $department):?>
                <div class="uk-card uk-card-default uk-card-body uk-card-hover">
                    <article class="uk-article">
                        <h3><span uk-icon="tag"></span>&nbsp;&nbsp;<?= $department['title'];?></h3>
                        <hr>
                        <h4><?= __('Persons');?></h4>
                        <ul class="uk-list">
                            <?php foreach ($persons as $person): ?>
                                <?php if($person['department_id'] == $department['id']): ?>
                                    <li><?= $person['name'] . ' ' . $person['lastname'];?></li>
                                <?php endif;?>
                            <?php endforeach;?>
                        </ul>

                        <h5><?= __('Actions');?></h5>
                        <input class="uk-checkbox" type="checkbox" name="trash[]" value="<?= $department['id'];?>">&nbsp;
                        <a href="#" uk-toggle="target: #trash-confirm" class="uk-icon-link uk-margin-right-small" uk-icon="icon: trash"></a>
                        <span class="uk-margin-right-small"><?= __('Trash selected');?></span>
                        <a href="<?= BASE_URL . '/departments/edit/' . $department['id'];?>" class="uk-icon-link uk-margin-right-small" uk-icon="file-edit"></a>
                        <span><?= __('Edit');?></span>
                    </article>
                </div>
            <?php endforeach;?>
        </div>
    </form>
    <?= $modal; ?>
<?php endif;?>
