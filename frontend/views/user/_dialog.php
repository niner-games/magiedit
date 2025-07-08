<?php

/** @var yii\web\View $this */

$this->registerJs("
    let deleteUrl = '';
    const csrfParam = '".Yii::$app->request->csrfParam."';
    const csrfToken = '".Yii::$app->request->getCsrfToken()."';
    
    $(document).on('click', '.delete-user-btn', function (e) {
        e.preventDefault();
        const btn = $(this);
        deleteUrl = $(this).attr('href');
        
        $('#delete-user-type').text(btn.data('modal-type'));
        $('#delete-user-status').text(btn.data('modal-status'));
        $('#delete-user-username').text(btn.data('modal-username'));
        
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        
        modal.show();
    });

    $('#confirmDeleteBtn').on('click', function () {
        if (deleteUrl) {
            const form = $('<form>', {
                method: 'POST',
                action: deleteUrl
            });

            const csrfInput = $('<input>', {
                type: 'hidden',
                name: csrfParam,
                value: csrfToken
            });

            form.append(csrfInput).appendTo('body');
            form.submit();
            
            form.on('submit', function () {
                $(this).remove();
            });
        }
    });
");

$this->registerCss("
    @media (max-width: 575.98px) {
        .modal-dialog.modal-sm {
            max-width: 200px;
            margin: auto;
        }
    }
    
    .modal-dialog.modal-sm {
        max-width: 330px;
        margin: auto;
    }
");

?>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= Yii::t('frontend-views', 'The following user will be deleted:'); ?></p>
                <ul id="delete-user-details" style="">
                    <li><?= Yii::t('common-models', 'Username'); ?>: <strong><span id="delete-user-username"></span></strong></li>
                    <li><?= Yii::t('common-models', 'Status'); ?>: <strong><span id="delete-user-status"></span></strong></li>
                    <li><?= Yii::t('common-models', 'Type'); ?>: <strong><span id="delete-user-type"></span></strong></li>
                </ul>
                <p><?= Yii::t('frontend-views', 'Are you sure?'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= Yii::t('frontend-views', 'No'); ?></button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn"><?= Yii::t('frontend-views', 'Yes'); ?></button>
            </div>
        </div>
    </div>
</div>
