<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $activation_code
 * @property string $email
 * @property string $create_time
 * @property string $last_visit
 * @property string $role
 * @property integer $status
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $birthday
 *
 * @property Profile $profile
 */
class User extends CActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['email', 'required', 'except'=>'checkout'],
            ['email', 'email'],
            ['status', 'numerical', 'integerOnly' => true],
            ['username, role', 'length', 'max' => 20],
            ['password, email', 'length', 'max' => 128],
            ['first_name, last_name', 'length', 'max' => 100],
            ['gender', 'length', 'max' => 1],
            ['last_visit, birthday', 'safe'],
            ['birthday', 'date', 'format' => 'yyyy-MM-dd'],
            ['id, username, password, email, create_time, last_visit, role, status, first_name, last_name, gender, birthday', 'safe', 'on' => 'search'],
        ];
    }

    protected function beforeValidate()
    {
        if (isset($_POST['User']['birth_day'], $_POST['User']['birth_month'], $_POST['User']['birth_year'])) {
            $this->birth_year = $_POST['User']['birth_year'];
            $this->birth_month = str_pad($_POST['User']['birth_month'], 2, '0', STR_PAD_LEFT);
            $this->birth_day = str_pad($_POST['User']['birth_day'], 2, '0', STR_PAD_LEFT);
            $this->birthday = $this->birth_year . '-' . $this->birth_month . '-' . $this->birth_day;
        }
        return parent::beforeValidate();
    }

    protected function afterFind()
    {
        if ($this->birthday) {
            list($year, $month, $day) = explode('-', $this->birthday);
            $this->birth_day = ltrim($day, '0');
            $this->birth_month = ltrim($month, '0');
            $this->birth_year = $year;
        }
        parent::afterFind();
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'profile' => [self::HAS_ONE, 'Profile', 'user_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'create_time' => 'Create Time',
            'last_visit' => 'Last Visit',
            'role' => 'Role',
            'status' => 'Status',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'birthday' => 'Birthday',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('last_visit', $this->last_visit, true);
        $criteria->compare('role', $this->role, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('gender', $this->gender, true);
        $criteria->compare('birthday', $this->birthday, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns User model by its email
     *
     * @param string $email
     * @access public
     * @return User
     */
    public function findByEmail($email)
    {
        return self::model()->findByAttributes(['email' => $email]);
    }

    public function validatePassword($password)
    {
        return Bcrypt::verify($password, $this->password);
    }

    public static function getGenders()
    {
        return [
            'm' => t('Male'),
            'f' => t('Female'),
        ];
    }

    public $birth_day;
    public $birth_month;
    public $birth_year;

    public static function getMonths()
    {
        return app()->locale->getMonthNames();
    }

    public static function getDays()
    {
        $data = [];
        foreach (range(1, 31) as $number) {
            $data[$number] = $number;
        }
        return $data;
    }

    public static function getYears()
    {
        $data = [];
        foreach (range(2014, 1950) as $number) {
            $data[$number] = $number;
        }
        return $data;
    }

    public function createActivationCode($email = false){
        return  sha1(mt_rand(10000, 99999).time(). ($email ?: $this->email));
    }

    public function getFullname(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function setFullname($fullname){
        $split = explode(' ', $fullname);
        $this->first_name = array_shift($split);
        if(count($split)>0)
            $this->last_name = implode(' ', $split);

    }
}
