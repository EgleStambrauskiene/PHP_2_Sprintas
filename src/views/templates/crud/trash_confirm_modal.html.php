<div id="trash-confirm" uk-modal class="uk-flex-top">
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
        <h2 class="uk-modal-title"><?= __('Trash');?></h2>
        <p><?= __('This action can\'t to be undo. Are You sure to trash selected items?');?></p>
        <p class="uk-text-center">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-danger" type="button" onclick="event.preventDefault();getElementById('form-trash').submit()"><?= __('Trash');?></button>
        </p>
    </div>
</div>
