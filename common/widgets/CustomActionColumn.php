<?php

namespace common\widgets;

use Yii;
use yii\grid\ActionColumn;

/**
 * An extended yii\grid\ActionColumn that allows translation of confirmation message of the delete button and other
 * customizations of action column of yii\grid\GridView in an easy manner.
 *
 * Usage example
 *
 * ```php
 * [
 *     'class' => CustomActionColumn::class,
 *     'deleteConfirmationText' => 'Your custom delete text!',
 * ],
 * ```
 *
 * @author Tomasz Trejderowski <tomasz.trejderowski@wst.pl>
 *
 * Partially inspired by: https://stackoverflow.com/a/44182183/1469208
 */
class CustomActionColumn extends ActionColumn
{
    /**
     * @var string message displayed as a confirmation of clicking "Delete" button
     */
    public string $deleteConfirmationText = 'Are you sure you want to delete this item?';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->initDefaultButtons();
        $this->headerOptions = ['style' => 'width: 4%'];
    }

    /**
     * Overrides the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open', ['class' => 'awfully-black-button']);
        $this->initDefaultButton('update', 'pencil', ['class' => 'awfully-black-button']);
        $this->initDefaultButton('delete', 'trash', [
            'data-method' => 'post',
            'class' => 'awfully-black-button',
            'data-confirm' => $this->deleteConfirmationText,
        ]);
    }
}
