<?php
/** Created by griga at 28.06.2014 | 9:36.
 * 
 */

class RegistrationForm extends CFormModel {

    public $name;
    public $email;
    public $password;
    public $password_repeat;
    public $subscribe;
    public $captcha;

    private $first_name;
    private $last_name;

    public function rules()
    {
        return [
            ['email, name, password, password_repeat','required'],
            ['email','email'],
            ['email', 'unique', 'className' => 'User',
                'attributeName' => 'email',
                'message'=>ts('This Email is already in use')],
            ['subscribe','boolean'],
            ['password','length','min'=>6],
            ['password','passwordRepeat'],

            ['captcha','captcha','allowEmpty'=>!Yii::app()->user->isGuest || !CCaptcha::checkRequirements(),]
        ];
    }

    public function passwordRepeat($attribute, $params){
        if($this->password && $this->password_repeat && $this->password != $this->password_repeat){
            $this->addError('password',ts('Password mismatch'));
        }
    }

    public function attributeLabels(){
        return [
            'name'=>ts('Name'),
            'email'=>ts('Email Address'),
            'password'=>ts('Password'),
            'password_repeat'=>ts('Password Repeat'),
            'captcha'=>ts('Verify Code'),
        ];
    }

    protected function afterValidate()
    {
        $nameParts = explode(' ', $this->name);
        $this->first_name = array_shift($nameParts);
        $this->last_name = count($nameParts)>0 ? implode($nameParts, ' ') : '';
        parent::afterValidate();
    }


    public function register(){
        $user = new User();
        $user->email = $this->email;
        $user->password = Bcrypt::hash($this->password);
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->status = User::STATUS_NOT_ACTIVE;
        $user->activation_code = $user->createActivationCode();
        $user->role = 'guest';
        if($user->save()){
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->subscribe = $this->subscribe;
            if($profile->save()){
                user()->login(new UserIdentity($user->email,$user->password),3600*24*30);
                MailService::send('registration_email',[
                    'sitename'=>Config::get('site_email_from'),
                    'sitemail'=>Config::get('site_email_address'),
                    'username'=>$this->name,
                    'email'=>$user->email,
                    'avtivation_url'=>app()->createAbsoluteUrl('user/auth/activate',['code'=>$user->activation_code]),
                    'siteurl'=>app()->createAbsoluteUrl('site/index'),
                ],$this->email);
                return true;
            }
        }
        return false;
    }


} 