<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string  $username
 * @property string  $password
 * @property string  $email
 * @property string  $last_password_change
 * @property bool    $password_change_force
 */
class User extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tbl_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['username, password, email', 'required'],
            ['username, password, email', 'length', 'max' => 128],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, username, password, email', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                    => 'ID',
            'username'              => 'Username',
            'password'              => 'Password',
            'email'                 => 'Email',
            'last_password_change'  => 'Last password change',
            'password_change_force' => 'Password Change Force Flag',
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
     * Authenticate user
     * @return bool
     */
    public function authenticate()
    {
        $user = $this->getUser();
        $user->validatePassword($this->password);
        return $user->checkIfPasswordExpired();
    }

    /**
     * @return User
     * @throws InvalidLoginException
     * @throws InvalidPasswordException
     */
    private function getUser()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('username', $this->username);

        $user = $this->find($criteria);
        if (empty($user)) {
            throw new InvalidLoginException();
        }
        return $user;
    }

    /**
     * @param string $password
     * @return bool
     * @throws InvalidPasswordException
     */
    private function validatePassword($password)
    {
        $passwordValidation = CPasswordHelper::verifyPassword($password, $this->password);
        if (!$passwordValidation) {
            throw new InvalidPasswordException();
        }
        return true;
    }

    /**
     * @return bool
     * @throws PasswordExpiredException
     */
    private function checkIfPasswordExpired()
    {
        $passwordAgeInDays       = Yii::app()->params['passwordAgeInDays'];
        $passwordAgeInSeconds    = Converts::daysToSeconds($passwordAgeInDays);
        $currentTimestamp        = time();
        $passwordChangeTimestamp = strtotime($this->last_password_change);

        $timeDiff = $currentTimestamp - $passwordChangeTimestamp;

        if ($timeDiff < $passwordAgeInSeconds) {
            return true;
        }
        $this->password_change_force = true;
        $this->save();
        throw new PasswordExpiredException();
    }
}
