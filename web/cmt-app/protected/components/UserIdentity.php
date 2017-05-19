<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_PASSWORD_EXPIRED = 4;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
	    $model = User::model();

	    $model->username = $this->username;
	    $model->password = $this->password;
	    try {
            $this->errorCode = $model->authenticate() ? self::ERROR_NONE : self::ERROR_UNKNOWN_IDENTITY;
        } catch (\InvalidLoginException $e) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } catch (\InvalidPasswordException $e) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }  catch (\PasswordExpiredException $e) {
            $this->errorCode = self::ERROR_PASSWORD_EXPIRED;
        } catch (\Exception $e) {
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
        }
		return !$this->errorCode;
	}
}