<h1><?= __('Projects'); ?></h1>

<div class="uk-margin-bottom">
<a href="<?= BASE_URL . '/projects/add';?>" class="uk-icon-link uk-margin-right-small" uk-icon="icon: plus; ratio: 1.25"></a>
<span><?= __('Add record');?></span>
</div>

<?php if (!empty($projects)):?>
<div class="uk-margin-bottom">
<a href="<?= BASE_URL . '/projects/list';?>" class="uk-icon-link uk-margin-right-small" uk-icon="icon: list; ratio: 1.25"></a>
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

<form method="POST" action="<?= BASE_URL . '/projects/trash';?>" id="form-trash">
<div class="uk-child-width-1-1@s uk-child-width-1-2@m uk-child-width-1-2@l" uk-grid="masonry: true" uk-sortable>
<?php foreach ($projects as $project):?>
<div>
<div class="uk-card uk-card-default uk-card-body uk-card-hover">
<article class="uk-article">

<h3><span uk-icon="cog"></span>&nbsp;&nbsp;<?= $project['title'];?></h3>

<hr>
<h4></span>&nbsp;<?= 'Biudžetas: ' . $project['budget'] . ' Eurų';?></h4>
<h4></span>&nbsp;<?= 'Aprašymas: ' . $project['description'];?></h4>
<!-- <h4><= __('Department');?></h4> -->
<!-- <p><= $person['title'];?></p> -->
<h4><?= __('Persons');?></h4>
<ul class="uk-list">
<?php foreach ($project['person_id'] as $person): ?>
<li><?= $person['name'] . ' ' . $person['lastname'];?>
</li>
<?php endforeach;?>
</ul>

<h5><?= __('Actions');?></h5>
<input class="uk-checkbox" type="checkbox" name="trash[]" value="<?= $project['id'];?>">&nbsp;
<a href="#" uk-toggle="target: #trash-confirm" class="uk-icon-link uk-margin-right-small" uk-icon="icon: trash"></a>
<span class="uk-margin-right-small"><?= __('Trash selected');?></span>
<a href="<?= BASE_URL . '/projects/edit/' . $project['id'];?>" class="uk-icon-link uk-margin-right-small" uk-icon="file-edit"></a>
<span><?= __('Edit');?></span>
</article>

</div>
</div>
<?php endforeach;?>
</form>

<?= $modal; ?>
<?php endif;?>
