<h1><?= __('Projects'); ?></h1>

<div class="uk-margin-bottom">
<a href="<?= BASE_URL . '/projects/add';?>" class="uk-icon-link uk-margin-right-small" uk-icon="icon: plus; ratio: 1.25"></a>
<span><?= __('Add record');?></span>
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

<?php if (!empty($projects)):?>
<div class="uk-margin-bottom">
<a href="<?= BASE_URL . '/projects/grid';?>" class="uk-icon-link uk-margin-right-small" uk-icon="icon: grid; ratio: 1.25"></a>
<span><?= __('Grid');?></span>
</div>

<div class="uk-overflow-auto">
<form method="POST" action="<?= BASE_URL ?>/projects/trash" id="form-trash">
<table class="uk-table uk-table-striped uk-table-small uk-table-justify uk-table-middle">
<thead>
<tr>
<th>
<a href="#" uk-toggle="target: #trash-confirm" class="uk-icon-link uk-margin-right-small" uk-icon="icon: trash; ratio: 1.25"></a>
</th>
<th><?= __('Edit'); ?></th>
<th><?= __('Title'); ?></th>
<th><?= __('Budget'); ?></th>
<th><?= __('Description'); ?></th>
<th><?= __('Persons'); ?></th>
</tr>
</thead>

<tbody>
<?php foreach ($projects as $index => $project): ?>

<tr>
<td>
<input id="trash-<?= $index;?>" class="uk-checkbox" type="checkbox" name="trash[]" value="<?= $project['id'];?>">
</td>

<td>
<a href="<?= BASE_URL . '/projects/edit/' . $project['id'];?>" class="uk-icon-link uk-margin-right-small" uk-icon="file-edit"></a>
</td>

<td><?= $project['title']; ?></td>
<td><?= $project['budget']; ?></td>
<td><?= $project['description'];?></td>
<td>
<ul class="uk-list">

<?php foreach ($project['person_id'] as $person): ?>
<li><?= $person['name' . 'lastname'];?></li>
<?php endforeach;?>

</ul>
</td>

</tr>
<?php endforeach;?>
</tbody>
</table>
</form>
</div>

<?= $modal; ?>
<?php endif;?>
