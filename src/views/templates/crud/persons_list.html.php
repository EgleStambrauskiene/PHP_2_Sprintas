<h1><?= __('Persons'); ?></h1>

<div class="uk-margin-bottom">
<a href="<?= BASE_URL . '/persons/add';?>" class="uk-icon-link uk-margin-right-small" uk-icon="icon: plus; ratio: 1.25"></a>
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

<?php if (!empty($persons)):?>
<div class="uk-margin-bottom">
<a href="<?= BASE_URL . '/persons/grid';?>" class="uk-icon-link uk-margin-right-small" uk-icon="icon: grid; ratio: 1.25"></a>
<span><?= __('Grid');?></span>
</div>

<div class="uk-overflow-auto">
<form method="POST" action="<?= BASE_URL ?>/persons/trash" id="form-trash">
<table class="uk-table uk-table-striped uk-table-small uk-table-justify uk-table-middle">
<thead>
<tr>
<th>
<a href="#" uk-toggle="target: #trash-confirm" class="uk-icon-link uk-margin-right-small" uk-icon="icon: trash; ratio: 1.25"></a>
</th>
<th><?= __('Edit'); ?></th>
<th><?= __('Name'); ?></th>
<th><?= __('Lastname'); ?></th>
<th><?= __('Department'); ?></th>
<th><?= __('Projects'); ?></th>
</tr>
</thead>

<tbody>
<?php foreach ($persons as $index => $person): ?>

<tr>
<td>
<input id="trash-<?= $index;?>" class="uk-checkbox" type="checkbox" name="trash[]" value="<?= $person['id'];?>">
</td>

<td>
<a href="<?= BASE_URL . '/persons/edit/' . $person['id'];?>" class="uk-icon-link uk-margin-right-small" uk-icon="file-edit"></a>
</td>

<td><?= $person['name']; ?></td>
<td><?= $person['lastname']; ?></td>
<td><?= $person['title'];?></td>
<td>
<ul class="uk-list">

<?php foreach ($person['project_id'] as $project): ?>
<li><?= $project['title'];?></li>
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
