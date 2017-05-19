<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class PasswordExpiredForm extends CFormModel
{
    /** @var  string */
    public $password;
    /** @var  string */
    public $username;
    /** @var  string */
    public $newPasswordRepeat;
    /** @var string */
    public $newPassword;

    /** @var UserIdentity */
    private $_identity;
    /** @var  User */
    private $_user;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            // username and password are required
            ['newPassword, newPasswordRepeat, username, password', 'required'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'newPasswordRepeat' => 'Repeated new password',
        ];
    }

    /**
     * @return bool
     */
    public function changePassword()
    {
        try {
            if (!$this->validate()) {
                throw new ValidationException();
            }

            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();

            if ($this->_identity->errorCode !== UserIdentity::ERROR_NONE
                && $this->_identity->errorCode !== UserIdentity::ERROR_PASSWORD_EXPIRED
            ) {
                $this->addError('password', 'Incorrect username or password.');
                throw new InvalidLoginException();
            }

            if ($this->newPassword !== $this->newPasswordRepeat) {
                $this->addError('newPasswordRepeat', 'Passwords are not the same');
                throw new PasswordNoMatchException();
            }

            $this->setUser();
            $this->checkPasswordUses();
            $this->archiveOldPassword();
            $this->saveNewPassword();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function setUser()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('username', $this->username);
        $userModel           = User::model();
        $userModel->username = $this->username;
        $this->_user         = $userModel->find($criteria);
    }

    private function saveNewPassword()
    {
        $this->_user->password              = CPasswordHelper::hashPassword($this->newPassword);
        $this->_user->last_password_change  = date('Y-m-d H:i:s', time());
        $this->_user->password_change_force = false;
        $this->_user->save();
    }

    /**
     * @throws NewPasswordException
     */
    private function checkPasswordUses()
    {
        if ($this->password === $this->newPassword) {
            $this->addError('newPassword', 'Password was used before');
            throw new NewPasswordException('Password was used last time');
        }
        $passwordHistoryModel          = new UserPasswordsHistory();
        $passwordHistoryModel->user_id = $this->_user->id;
        $passwordHistory               = $passwordHistoryModel->findAll();
        if (empty($passwordHistory)) {
            return;
        }
        foreach ($passwordHistory as $password) {
            if (CPasswordHelper::verifyPassword($this->newPassword, $password->password)) {
                $this->addError('newPassword', 'Password was used before');
                throw new NewPasswordException('Password was used before');
            }
        }
    }

    private function archiveOldPassword()
    {
        $passwordHistoryModel           = new UserPasswordsHistory();
        $passwordHistoryModel->user_id  = $this->_user->id;
        $passwordHistoryModel->password = $this->_user->password;
        $passwordHistoryModel->save();
    }

}
