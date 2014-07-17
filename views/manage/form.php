<?php
$this->widget('ext.yg.WellBlocksCollapsible');
/**
 * @var $this BackendController
 * @var $form \yg\tb\ActiveForm
 * @var User $model
 * @var Profile $profile
 */

$this->breadcrumbs = array(
    t('User Module') => '/admin/user/',
    t('List of users') => '/admin/user/manage',
    t($model->isNewRecord ? 'New record' : 'Update record'),
);


$form = $this->beginWidget('\yg\tb\ActiveForm', array(
    'id' => 'user-form',
    'labelColWidth' => 3,
));

?>

<h3><?= t($model->isNewRecord ? 'New record' : 'Update record') ?></h3>
<hr/>
<div class="row">
    <div class="col-sm-6">
        <div class="well">
            <h4><?= t('User info') ?></h4>
            <?= $form->textControl($model, 'email'); ?>
            <?= $form->textControl($model, 'first_name'); ?>
            <?= $form->textControl($model, 'last_name'); ?>
            <div class="form-group ">
                <?=
                CHtml::activeLabelEx($model, 'birthday', array(
                    'class' => 'control-label col-sm-3'
                )); ?>
                <div class="col-sm-9">
                    <div class="col-xs-3 p0">
                        <?= $form->dropDownList($model, 'birth_day',
                            User::getDays(),
                            [
                                'empty'=>t('Day'),
                                'class' => 'form-control',
                            ]); ?>
                    </div>
                    <div class="col-xs-5 p0">
                        <?=
                        $form->dropDownList($model, 'birth_month', User::getMonths(), array(
                            'class' => 'form-control',
                            'prompt' => t('Month'),
                        )); ?>
                    </div>
                    <div class="col-xs-4 p0">
                        <?=
                        $form->dropDownList($model, 'birth_year', User::getYears(), array(
                            'class' => 'form-control',
                            'empty' => t('Year'),
                        )); ?>
                    </div>
                    <?= $form->error($model, 'birthday') ?>
                </div>
            </div>
            <?= $form->dropDownControl($model, 'gender', User::getGenders(), array('empty' => '')); ?>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="well">
            <h4><?= t('User profile') ?></h4>
            <?= $form->textAreaControl($profile, 'address'); ?>
            <?= $form->textControl($profile, 'city'); ?>
            <?= $form->textControl($profile, 'phone'); ?>
            <?= $form->dropDownControl($profile, 'lang', Lang::getLanguages(), array('empty' => '')); ?>
        </div>
    </div>

</div>


<?= $form->actionButtons($model) ?>

<?php $this->endWidget(); ?>

