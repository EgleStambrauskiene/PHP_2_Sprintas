<h1>Department</h1>

<form class="uk-grid uk-form-stacked" uk-grid method="POST" action="<?= BASE_URL; ?>/departments/save">
    <fieldset class="uk-fieldset uk-width-1-1@s uk-width-1-2@m">
        <legend class="uk-legend"><?= __('Add');?></legend>
        <div class="uk-margin">
            <label class="uk-form-label" for="name"><?= __('Title');?></label>
            <input type="text" id="title" class="uk-input uk-form-width-large" name="title"/>
        </div>

        <div class="uk-margin">
            <label class="uk-form-label" for="person_id"><?= __('Person');?></label>
            <p>Dėmesio: asmuo gali priklausyti tik vienam departamentui.</p>
            <p>Departamentą pasirinkti/pakeisti galima Person/Edit arba Person/Add formose.</p>
            <p>Asmenų, nepriklausančių jokiam departamentui, sąrašas:</p>
            <ul class="uk-list">
                <?php foreach ($persons as $person):?>
                    <?php if(!$person['department_id']): ?>
                        <li>
                            <!-- <input type="checkbox" name="person_id[]" value="<= $person['id'];?>" class="uk-checkbox"> -->
                            <span>
                                <?= $person['name'] . ' ' .$person['lastname'];?>
                            </span>
                        </li>
                    <?php endif; ?>
                <?php endforeach;?>
            </ul>
        </div>

        <div class="uk-margin">
            <button type="submit" class="uk-button uk-button-primary"><?= __('Add'); ?></button>&nbsp;
            <a href="<?= BASE_URL . '/departments' . '/' . $_SESSION['listmode'];?>" class="uk-button uk-button-default"><?= __('Cancel'); ?></a>
        </div>
    </fieldset>
</form>
