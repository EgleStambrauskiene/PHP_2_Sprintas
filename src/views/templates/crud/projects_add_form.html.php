<h1>Project</h1>

<form class="uk-grid uk-form-stacked" uk-grid method="POST" action="<?= BASE_URL; ?>/projects/save">

<fieldset class="uk-fieldset uk-width-1-1@s uk-width-1-2@m">
<legend class="uk-legend"><?= __('Add');?></legend>

<div class="uk-margin">
<label class="uk-form-label" for="title"><?= __('Title');?></label>
<input type="text" id="title" class="uk-input uk-form-width-large" name="title"/>
</div>

<div class="uk-margin">
<label class="uk-form-label" for="budget"><?= __('Budget');?></label>
<input type="text" id="budget" class="uk-input uk-form-width-large" name="budget"/>
</div>


<!-- <div class="uk-margin">
<label class="uk-form-label" for="department_id"><?= __('Department');?></label>
<select class="uk-select uk-form-width-large" id="department_id" name="department_id">
<option value="" selected><= __('Not assigned');?></option>
<php foreach ($departments as $department):?>
<option value="<= $department['id'];?>"><= $department['title'];?></option>
<php endforeach;?>
</select>
</div> -->


<div class="uk-margin">
<ul class="uk-list">
<?php foreach ($projects as $project):?>
<li>
<input type="checkbox" name="person_id[]" value="<?= $person['id'];?>" class="uk-checkbox">
<span><?= $person['name' . 'lastname'];?></span>
</li>
<?php endforeach;?>
</ul>
</div>


<div class="uk-margin">

<button type="submit" class="uk-button uk-button-primary"><?= __('Add'); ?></button>&nbsp;

<a href="<?= BASE_URL . '/projects' . '/' . $_SESSION['listmode'];?>" class="uk-button uk-button-default"><?= __('Cancel'); ?></a>

</div>

</fieldset>
</form>
