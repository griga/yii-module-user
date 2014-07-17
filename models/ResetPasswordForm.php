<?php

class ResetPasswordForm extends CFormModel{

    public $password;
    public $password_repeat;
    public $code;

    public function rules()
    {
        return [
            ['password, password_repeat','required'],
            ['password','length','min'=>6],
            ['password','passwordRepeat'],
            ['code','safe'],
        ];
    }

    public function passwordRepeat($attribute, $params){
        if($this->password && $this->password_repeat && $this->password != $this->password_repeat){
            $this->addError('password',ts('Password mismatch'));
        }
    }

    public function attributeLabels()
    {
        return [
            'password'=>ts('Password'),
            'password_repeat'=>ts('Password Repeat'),
        ];
    }

    public function updatePassword(){
        /** @var User $user */
        $user = User::model()->find('activation_code=:ac',[':ac'=>$this->code]);
        if($user){
            $password = Bcrypt::hash($this->password);
            db()->createCommand()->update('{{user}}',[
                'password'=>$password,
            ],'activation_code=:ac',[':ac'=>$this->code]);
            user()->login(new UserIdentity($user->email,$password),3600*24*30);
        }
    }

}