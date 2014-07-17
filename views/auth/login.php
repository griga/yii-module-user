<?php
/** Created by griga at 26.06.2014 | 18:09.
 * @var $this FrontEndController
 * @var $form CActiveForm
 * @var $registrationModel RegistrationForm
 * @var $loginModel LoginForm
 */
?>

<div class="container content-container">
    <div class="row">
        <div class="col-sm-4 col-sm-offset-1">
            <h4><?= ts('Sign In') ?></h4>
            <?php $form = $this->beginWidget('FrontForm', [
                'id' => 'login-form',
                'action' => app()->createUrl('user/auth/login'),
            ]) ?>
            <div class="form-group">
                <?= $form->labelEx($loginModel, 'username') ?>
                <?= $form->textField($loginModel, 'username', [
                    'class' => 'form-control',
                    'placeholder' => $loginModel->getAttributeLabel('username'),
                ]) ?>
                <?= $form->error($loginModel, 'username') ?>
            </div>

            <div class="form-group">
                <?= $form->labelEx($loginModel, 'password') ?>
                <?= $form->passwordField($loginModel, 'password', [
                    'class' => 'form-control',
                    'placeholder' => $loginModel->getAttributeLabel('password'),
                ]) ?>
                <?= $form->error($loginModel, 'password') ?>
            </div>

            <div class="checkbox">
                <label>
                    <?= $form->checkBox($loginModel, 'rememberMe') ?> <?= ts('Remember me') ?>
                </label>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
            <?php $this->endWidget() ?>
            <hr/>
            <a href="<?= app()->createUrl('user/auth/forgot-password') ?>"><?= ts('Forgot password?') ?></a>
        </div>
        <div class="col-sm-4 col-sm-offset-1">
            <h4><?= ts('Registration') ?></h4>
            <?php $form = $this->beginWidget('FrontForm', [
                'id' => 'registration-form',
                'action' => app()->createUrl('user/auth/register'),
            ]) ?>
            <div class="form-group">
                <?= $form->labelEx($registrationModel, 'name') ?>
                <?= $form->textField($registrationModel, 'name', [
                    'class' => 'form-control',
                    'placeholder' => $registrationModel->getAttributeLabel('name'),
                ]) ?>
                <?= $form->error($registrationModel, 'name') ?>
            </div>
            <div class="form-group">
                <?= $form->labelEx($registrationModel, 'email') ?>
                <?= $form->textField($registrationModel, 'email', [
                    'class' => 'form-control',
                    'placeholder' => $registrationModel->getAttributeLabel('email'),
                ]) ?>
                <?= $form->error($registrationModel, 'email') ?>
            </div>
            <div class="form-group">
                <?= $form->labelEx($registrationModel, 'password') ?>
                <?= $form->passwordField($registrationModel, 'password', [
                    'class' => 'form-control',
                    'placeholder' => $registrationModel->getAttributeLabel('password'),
                ]) ?>
                <?= $form->error($registrationModel, 'password') ?>
            </div>
            <div class="form-group">
                <?= $form->labelEx($registrationModel, 'password_repeat') ?>
                <?= $form->passwordField($registrationModel, 'password_repeat', [
                    'class' => 'form-control',
                    'placeholder' => $registrationModel->getAttributeLabel('password_repeat'),
                ]) ?>
                <?= $form->error($registrationModel, 'password_repeat') ?>
            </div>
            <div class="checkbox">
                <label>
                    <?= $form->checkBox($registrationModel, 'subscribe') ?><?= ts('Subscribe to newsletter') ?>
                </label>
            </div>
            <div class="form-group">
                <? if (CCaptcha::checkRequirements() && Yii::app()->user->isGuest): ?>
                    <?= $form->labelEx($registrationModel, 'captcha') ?>
                    <?= $form->textField($registrationModel, 'captcha', [
                        'class' => 'form-control',
                        'placeholder' => $registrationModel->getAttributeLabel('captcha'),
                    ]) ?>
                    <? $this->widget('CCaptcha') ?>
                    <?= $form->error($registrationModel, 'captcha') ?>
                <? endif ?>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
            <?php $this->endWidget() ?>
            <hr/>
            <?php $this->widget('ext.hoauth.widgets.HOAuth'); ?>
        </div>
    </div>

</div>