<h1>Department</h1>

<form class="uk-grid uk-form-stacked" uk-grid method="POST" action="<?= BASE_URL; ?>/departments/save">
    <input type="hidden" name="id" value="<?= $department[0]['id'] ;?>">

    <fieldset class="uk-fieldset uk-width-1-1@s uk-width-1-2@m">
        <legend class="uk-legend"><?= __('Edit');?></legend>

        <div class="uk-margin">
            <label class="uk-form-label" for="name"><?= __('Title');?></label>
            <input type="text" id="title" class="uk-input uk-form-width-large" name="title" value="<?= $department[0]['title']; ?>"/>
        </div>

        <div class="uk-margin">
            <button type="submit" class="uk-button uk-button-primary"><?= __('Save'); ?></button>&nbsp;
            <a href="<?= BASE_URL . '/departments' . '/' . $_SESSION['listmode'];?>" class="uk-button uk-button-default"><?= __('Cancel');?></a>
        </div>
    </fieldset>
</form>
