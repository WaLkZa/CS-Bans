<?php
/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

/**
 * Модель для таблицы "{{webadmins}}".
 *
 * Доступные поля таблицы '{{webadmins}}':
 * @property integer $id ID веб админа
 * @property string $username Логин
 * @property string $password Пароль
 * @property integer $level Уровень
 * @property string $logcode Хуйпоймичё =)
 * @property string $email Почта
 * @property integer $last_action Последний визит
 * @property integer $try Кол-во попыток
 */
class Webadmins extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{webadmins}}';
	}

	public function rules()
	{
		return array(
			array('level', 'numerical', 'integerOnly'=>true),
			array('username, password', 'length', 'max'=>32),
			array('username, password, email', 'required'),
			array('username, email','unique'),
			array('email', 'length', 'max'=>64),
			array('email', 'email'),
			array('id, username, password, level, logcode, email, last_action, try', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'levels' => array(self::BELONGS_TO, 'Levels', 'level'),
		);
	}

	/**
	 * Проверка прав пользователя
	 * @param type $access Поле в таблице уровней
	 * @param type $username Ник админа в банах
	 * @return boolean
	 */
	public static function checkAccess($access = NULL, $username = '') {

		// Главному админу можно всё
		if(Yii::app()->user->id == '1')
			return TRUE;

		if(Yii::app()->user->isGuest || !$access) {
			return FALSE;
		}

		if(isset($this) && get_class($this) == 'Webadmins') {
			$model = &$this;
		}
		else {
			$model = Webadmins::model()->with('levels')->findByPk(Yii::app()->user->id);
		}

		//exit($model->levels->$access);

		if(
			isset($model->levels->$access)
				&&
			(
				$model->levels->$access === 'yes'
					||
				(
					$model->levels->$access === 'own'
						&&
					strtolower($username) === strtolower(Yii::app()->user->name)
				)
			)
		) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Проверяет пароль
	 * @param string $password
	 * @return bool
	 */
	public function validatePassword($password) {
		return $this->hashPassword($password) === $this->password;
	}

	/**
	 * Возвращает хэш-сумму пароля
	 * @param string $password
	 * @return string
	 */
	public function hashPassword($password) {
		return md5($password);
    }

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Име',
			'password' => 'Парола',
			'level' => 'Ниво',
			'logcode' => 'Logcode',
			'email' => 'Имейл',
			'last_action' => 'Последно действие',
			'try' => 'Опити',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('logcode',$this->logcode,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('last_action',$this->last_action);
		$criteria->compare('try',$this->try);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	// Перед проверкой
	public function beforeValidate() {
		parent::beforeValidate();

		if(!$this->isNewRecord) {

			$oldRecord = Webadmins::model()->findByPk($this->id);

			if($this->password && $oldRecord->password !== $this->password)
				$this->password = md5($this->password);
			else
				$this->password = $oldRecord->password;

		}
		else {
			$this->password = md5($this->password);
		}
		return TRUE;
	}

	public static function getList() {
		$admins = self::model()->findAll();

		$list = array();
		foreach($admins AS $admin) {
			$list[$admin->username] = $admin->username;
		}

		return $list;
	}

	public function afterSave() {
		if($this->isNewRecord)
			Syslog::add(Logs::LOG_ADDED, 'Добавен нов уеб админ <strong>' . $this->username . '</strong>');
		elseif($this->scenario !== 'auth')
			Syslog::add(Logs::LOG_EDITED, 'Променени детайли за уеб админ <strong>' . $this->username . '</strong>');
		return parent::afterSave();
	}

	public function afterDelete() {
		Syslog::add(Logs::LOG_DELETED, 'Изтрит уеб админ <strong>' . $this->username . '</strong>');
		return parent::afterDelete();
	}
}