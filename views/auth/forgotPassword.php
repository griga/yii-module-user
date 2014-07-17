<?php
/** Created by griga at 26.06.2014 | 18:09.
 * @var $this FrontEndController
 * @var $form CActiveForm
 * @var $model ForgotPasswordForm
 */
?>

<div class="container content-container">
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <h4><?= ts('Forgot Password') ?></h4>
            <p>Укажите логин, для которого вы хотите восстановить пароль.</p>
            <hr/>
            <?php $form = $this->beginWidget('CActiveForm',[
                'id'=>'forgot-password-form',
                'action' =>app()->createUrl('user/auth/forgot-password'),
                'enableAjaxValidation'=>true,
                'errorMessageCssClass'=>'label label-danger',
                'clientOptions'=>[
                    'validateOnSubmit'=>true,
                    'validateOnType'=>false,
                    'validateOnChange'=>false,
                ],
                'htmlOptions'=>[
                    'role'=>'form'
                ]
            ]) ?>
                <div class="form-group">
                    <?= $form->labelEx($model, 'email') ?>
                    <?= $form->textField($model, 'email', [
                        'class'=>'form-control',
                        'placeholder'=>$model->getAttributeLabel('email'),
                    ]) ?>
                    <?= $form->error($model, 'email') ?>
                </div>

                <button type="submit" class="btn btn-default">Submit</button>
            <?php $this->endWidget() ?>
            <hr/>
            <?php $this->widget('ext.hoauth.widgets.HOAuth'); ?>
        </div>
    </div>

</div>