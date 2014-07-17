<?php
/** @var User $model */
/** @var AdminController $this */

$this->breadcrumbs = array(
    t('User')=>'user',
    t('List of users')
);
$countAll = $model->search()->totalItemCount;

$this->widget('ext.yg.GridFilterClearButtons', array(
    'gridId'=>'page-grid',
))

?>

<div class="row">
    <div class="col-sm-12">
        <h3><?php echo t('{n} user|{n} users|{n} users', $countAll); ?></h3>
        <a class="btn btn-success btn-xs" href="/admin/user/manage/create"><span class="glyphicon glyphicon-plus"></span> <?= t('add') ?></a>
        <?php $this->widget('\yg\tb\GridView', array(
            'id' => 'user-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                'email',
                array(
                    'class' => '\yg\tb\ButtonColumn',
                ),
            )
        )); ?>
    </div>
</div>