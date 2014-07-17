<?php

class ManageController extends BackendController
{
	public function actionIndex()
	{
        $model = new User('search');
        $model->unsetAttributes();
        if(isset($_GET['User']))
            $model->attributes = $_GET['User'];

		$this->render('index', array(
            'model'=>$model
        ));
	}

    /**
     *
     */
    public function actionCreate()
    {
        $model = new User();
        $profile = new Profile();

        $this->performAjaxValidation($model);

        if(isset($_POST['User'], $_POST['Profile'])){
            $model->attributes = $_POST['User'];
            $profile->attributes = $_POST['Profile'];
            if($model->save()){
                $profile->user_id = $model->id;
                $profile->save();
                if(isset($_POST['form_action']) && $_POST['form_action']==='save_and_continue'){
                    $this->redirect('update/'.$model->id);
                } else {
                    $this->redirect('index');
                }
            }
        }
        $this->render('form', array(
            'model'=>$model,
            'profile'=>$profile,
        ));
    }

    /**
     *
     */
    public function actionUpdate($id)
    {
        /** @var User $model */
        $model = User::model()->findByPk($id);
        $profile = $model->profile ?: new Profile();

        $this->performAjaxValidation($model);

        if(isset($_POST['User'], $_POST['Profile'])){
            $model->attributes = $_POST['User'];
            $profile->attributes = $_POST['Profile'];
            if($model->save()){
                $profile->user_id = $model->id;
                $profile->save();
                if(isset($_POST['form_action']) && $_POST['form_action']==='save_and_continue'){
                    $this->redirect(array('update','id'=>$model->id));
                } else {
                    $this->redirect(array('index'));
                }
            }
        }
        $this->render('form', array(
            'model'=>$model,
            'profile'=>$profile,
        ));
    }

    private function performAjaxValidation($model){
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}