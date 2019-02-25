<h1>Project</h1>

<form class="uk-grid uk-form-stacked" uk-grid method="POST" action="<?= BASE_URL; ?>/projects/save">
<input type="hidden" name="id" value="<?= $project[0]['id'] ;?>">

<fieldset class="uk-fieldset uk-width-1-1@s uk-width-1-2@m">
<legend class="uk-legend"><?= __('Edit');?></legend>

<div class="uk-margin">
<label class="uk-form-label" for="title"><?= __('Title');?></label>
<input type="text" id="title" class="uk-input uk-form-width-large" name="title" value="<?= $project[0]['title']; ?>"/>
</div>

<div class="uk-margin">
<label class="uk-form-label" for="budget"><?= __('Budget');?></label>
<input type="text" id="budget" class="uk-input uk-form-width-large" name="budget" value="<?= $project[0]['budget']; ?>"/>
</div>

<div class="uk-margin">
<label class="uk-form-label" for="description"><?= __('Description');?></label>
<input type="text" id="description" class="uk-input uk-form-width-large" name="description" value="<?= $project[0]['description']; ?>"/>
</div>


<!-- <div class="uk-margin">
<label class="uk-form-label" for="department_id"><= __('Department');?></label>
<select class="uk-select uk-form-width-large" id="department_id" name="department_id">
<option value="" <= isset($person[0]['department_id']) ? 'selected' : '';?>><= __('Not assigned');?></option>
<php foreach ($departments as $department):?>
<option value="<= $department['id'];?>" <= ($person[0]['department_id'] == $department['id']) ? 'selected' : '';?>><= $department['title'];?></option>
<php endforeach;?>
</select>
</div> -->


<div class="uk-margin">
<ul class="uk-list">
<?php foreach ($persons as $person):?>
<?php $checked = '';?>
<?php foreach ($project[0]['person_id'] as $assigned):?>
<?php if ($assigned['id'] == $person['id']):?>
<?php $checked = ' checked';?>
<?php break;?>
<?php endif;?>
<?php endforeach;?>
<li>
<input type="checkbox" name="person_id[]" value="<?= $person['id'];?>" class="uk-checkbox"<?= $checked;?>>
<span><?= $person['name'] . ' ' . $person['lastname'];?></span>
</li>
<?php endforeach;?>
</ul>
</div>


<div class="uk-margin">

<button type="submit" class="uk-button uk-button-primary"><?= __('Save'); ?></button>&nbsp;

<a href="<?= BASE_URL . '/projects' . '/' . $_SESSION['listmode'];?>" class="uk-button uk-button-default"><?= __('Cancel');?></a>

</div>

</fieldset>
</form>
