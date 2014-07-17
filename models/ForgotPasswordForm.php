<?php

class ForgotPasswordForm extends CFormModel{

    public $email;

    public function rules()
    {
        return [
            ['email','required'],
            ['email','email']
        ];
    }


    public function attributeLabels()
    {
        return [
            'email'=>ts('Email')
        ];
    }

    public function sendRemindCode(){
        $user = User::model()->findByEmail($this->email);
        if ($user) {
            $code = $user->createActivationCode();
            db()->createCommand()->update('{{user}}',[
                'activation_code'=>$code,
            ], 'email=:email', [
                ':email'=>$this->email,
            ]);
            MailService::send('forgot_password_email', [
                'sitename' => Config::get('site_email_from'),
                'sitemail' => Config::get('site_email_address'),
                'username' => $user->fullname,
                'email' => $user->email,
                'avtivation_url' => app()->createAbsoluteUrl('user/auth/reset-password', ['code' => $code]),
                'siteurl' => app()->createAbsoluteUrl('site/index'),
            ],$this->email);
        }
    }


}