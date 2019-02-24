<h1>Department</h1>

<form class="uk-grid uk-form-stacked" uk-grid method="POST" action="<?= BASE_URL; ?>/departments/save">

<fieldset class="uk-fieldset uk-width-1-1@s uk-width-1-2@m">
<legend class="uk-legend"><?= __('Add');?></legend>

<div class="uk-margin">
<label class="uk-form-label" for="name"><?= __('Title');?></label>
<input type="text" id="title" class="uk-input uk-form-width-large" name="title"/>
</div>

<!-- <div class="uk-margin">
<label class="uk-form-label" for="lastname"><= __('Lastname');?></label>
<input type="text" id="lastname" class="uk-input uk-form-width-large" name="lastname"/>
</div> -->


<div class="uk-margin">
<label class="uk-form-label" for="person_id"><?= __('Person');?></label>
<select class="uk-select uk-form-width-large" id="person_id" name="person_id">
<option value="" selected><?= __('Not assigned');?></option>
<?php foreach ($persons as $person):?>
<option value="<?= $person['id'];?>"><?= $person['name' . 'lastname'];?></option>
<?php endforeach;?>
</select>
</div>


<!-- <div class="uk-margin">
<ul class="uk-list">
<php foreach ($projects as $project):?>
<li>
<input type="checkbox" name="project_id[]" value="<= $project['id'];?>" class="uk-checkbox">
<span><= $project['title'];?></span>
</li>
<php endforeach;?>
</ul>
</div> -->


<div class="uk-margin">

<button type="submit" class="uk-button uk-button-primary"><?= __('Add'); ?></button>&nbsp;

<a href="<?= BASE_URL . '/departments' . '/' . $_SESSION['listmode'];?>" class="uk-button uk-button-default"><?= __('Cancel'); ?></a>

</div>

</fieldset>
</form>
