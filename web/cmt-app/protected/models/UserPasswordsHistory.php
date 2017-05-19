<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string  $user_id
 * @property string  $password
 */
class UserPasswordsHistory extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tbl_user_passwords_history';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['user_id, password', 'required'],
            ['password', 'length', 'max' => 128],
        ];
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

    public function getPasswordsCount()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('user_id', $this->user_id);
        return $this->count($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function save($runValidation = true, $attributes = null)
    {
        $maxPasswordHistoryCount = (int)Yii::app()->params['passwordHistoryCount'];
        $currentCount            = (int)$this->getPasswordsCount();
        if ($currentCount >= $maxPasswordHistoryCount) {
            $this->deleteFirstRecord();
        }
        return parent::save($runValidation, $attributes);
    }

    public function deleteFirstRecord()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('user_id', $this->user_id);
        $firstPassword = $this->find($criteria, ['order' => 'user_id ASC']);
        $firstPassword->delete();
    }


}
