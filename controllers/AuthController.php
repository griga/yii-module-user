<?php


class AuthController extends FrontendController
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return [
            'oauth' => [
                // the list of additional properties of this action is below
                'class' => 'ext.hoauth.HOAuthAction',
                // Yii alias for your user's model, or simply class name, when it already on yii's import path
                // default value of this property is: User
                'model' => 'User',
                // map model attributes to attributes of user's social profile
                // model attribute => profile attribute
                // the list of avaible attributes is below
                'attributes' => [
                    'email' => 'email',
                    'first_name' => 'firstName',
                    'last_name' => 'lastName',
                    'gender' => 'genderShort',
                    'birthday' => 'birthDate',
                    // you can also specify additional values,
                    // that will be applied to your model (eg. account activation status)
                    //'acc_status' => 1,
                ],
            ],
            'captcha' => array(
                'class' => 'CCaptchaAction',
            ),
        ];
    }


    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        if (user()->isGuest) {
            $loginModel = new LoginForm();
            $registrationModel = new RegistrationForm();

            $this->validateAjax('login-form', $loginModel);
            if (isset($_POST['LoginForm'])) {
                $loginModel->attributes = $_POST['LoginForm'];
                if ($loginModel->validate() && $loginModel->login())
                    $this->redirect(Yii::app()->user->returnUrl);
            }
            $this->render('login', [
                'loginModel' => $loginModel,
                'registrationModel' => $registrationModel,
            ]);
        } else {
            $this->redirect(app()->homeUrl);
        }
    }

    /**
     *
     */
    public function actionRegister()
    {
        if (user()->isGuest) {
            $registrationModel = new RegistrationForm();

            $this->validateAjax('registration-form', $registrationModel);

            if (isset($_POST['RegistrationForm'])) {
                $registrationModel->attributes = $_POST['RegistrationForm'];
                if ($registrationModel->validate() && $registrationModel->register())
                    $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        $this->redirect(app()->homeUrl);

    }

    public function actionActivate()
    {
        $code = r()->getParam('code');
        /** @var User $model */
        $model = User::model()->find('activation_code="' . $code . '"');
        if ($model && $model->activation_code == $code) {
            $model->status = User::STATUS_ACTIVE;
            $model->save();
            $this->redirect(app()->homeUrl);
        } else {
            throw new CHttpException(404, ts('Not found'));
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     *
     */
    public function actionForgotPassword()
    {
        $model = new ForgotPasswordForm;
        $this->validateAjax('forgot-password-form', $model);
        if (isset($_POST['ForgotPasswordForm'])){
            $model->attributes = $_POST['ForgotPasswordForm'];
            if($model->validate()){
                $model->sendRemindCode();
                user()->setFlash('forgot.password',ts('Forgot password send mail'));
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        $this->render('forgotPassword',[
            'model'=>$model
        ]);
    }

    /**
     *
     */
    public function actionResetPassword()
    {
        $model = new ResetPasswordForm();
        if(r()->getParam('code'))
            $model->code = r()->getParam('code');
        $this->validateAjax('reset-password-form', $model);
        if (isset($_POST['ResetPasswordForm'])){
            $model->attributes = $_POST['ResetPasswordForm'];
            if($model->validate()){
                $model->updatePassword();
                user()->setFlash('forgot.password.reset',ts('Forgot password reset'));
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        $this->render('resetPassword',[
            'model'=>$model
        ]);
    }




    private function validateAjax($formName, $model){
        if (isset($_POST['ajax']) && $_POST['ajax'] === $formName) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}