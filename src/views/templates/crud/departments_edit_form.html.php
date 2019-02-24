<h1>Department</h1>

<form class="uk-grid uk-form-stacked" uk-grid method="POST" action="<?= BASE_URL; ?>/departments/save">
<input type="hidden" name="id" value="<?= $department[0]['id'] ;?>">

<fieldset class="uk-fieldset uk-width-1-1@s uk-width-1-2@m">
<legend class="uk-legend"><?= __('Edit');?></legend>

<div class="uk-margin">
<label class="uk-form-label" for="name"><?= __('Title');?></label>
<input type="text" id="title" class="uk-input uk-form-width-large" name="title" value="<?= $department[0]['title']; ?>"/>
</div>

<!-- <div class="uk-margin">
<label class="uk-form-label" for="lastname"><= __('Lastname');?></label>
<input type="text" id="lastname" class="uk-input uk-form-width-large" name="lastname" value="<= $person[0]['lastname']; ?>"/>
</div> -->


<!-- <div class="uk-margin">
<label class="uk-form-label" for="department_id"><= __('Department');?></label>
<select class="uk-select uk-form-width-large" id="department_id" name="department_id">
<option value="" <= isset($person[0]['department_id']) ? 'selected' : '';?>><= __('Not assigned');?></option>
<php foreach ($departments as $department):?>
<option value="<= $department['id'];?>" <= ($person[0]['department_id'] == $department['id']) ? 'selected' : '';?>><= $department['title'];?></option>
<php endforeach;?>
</select>
</div> -->


<!-- <div class="uk-margin">
<ul class="uk-list">
<php foreach ($projects as $project):?>
<php $checked = '';?>
<php foreach ($person[0]['project_id'] as $assigned):?>
<php if ($assigned['id'] == $project['id']):?>
<php $checked = ' checked';?>
<php break;?>
<php endif;?>
<php endforeach;?>
<li>
<input type="checkbox" name="project_id[]" value="<= $project['id'];?>" class="uk-checkbox"<= $checked;?>>
<span><= $project['title'];?></span>
</li>
<php endforeach;?>
</ul>
</div> -->


<div class="uk-margin">

<button type="submit" class="uk-button uk-button-primary"><?= __('Save'); ?></button>&nbsp;

<a href="<?= BASE_URL . '/departments' . '/' . $_SESSION['listmode'];?>" class="uk-button uk-button-default"><?= __('Cancel');?></a>

</div>

</fieldset>
</form>
