<?php
/** Created by griga at 26.06.2014 | 18:09.
 * @var $this FrontEndController
 * @var $form FrontForm
 * @var $model ResetPasswordForm
 */
?>

<div class="container content-container">
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <h4><?= ts('Reset Password') ?></h4>

            <p><?= t('Please, type new password') ?></p>
            <hr/>
            <?php $form = $this->beginWidget('FrontForm', [
                'id' => 'reset-password-form',
                'action' => app()->createUrl('user/auth/reset-password'),
            ]) ?>
            <?= $form->hiddenField($model, 'code') ?>
            <div class="form-group">
                <?= $form->labelEx($model, 'password') ?>
                <?= $form->passwordField($model, 'password', [
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('password'),
                ]) ?>
                <?= $form->error($model, 'password') ?>
            </div>
            <div class="form-group">
                <?= $form->labelEx($model, 'password_repeat') ?>
                <?= $form->passwordField($model, 'password_repeat', [
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('password_repeat'),
                ]) ?>
                <?= $form->error($model, 'password_repeat') ?>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
            <?php $this->endWidget() ?>
            <hr/>
        </div>
    </div>

</div>