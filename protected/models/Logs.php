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
 * Модель для таблицы "{{logs}}".
 *
 * Доступные поля таблицы '{{logs}}':
 * @property integer $id ID записи
 * @property integer $timestamp Дата
 * @property string $ip IP
 * @property string $username Имя админа
 * @property string $action Действие
 * @property string $remarks Ремарка (?)
 */
class Logs extends CActiveRecord
{
	const LOG_ADDED = 'added';
	const LOG_EDITED = 'edited';
	const LOG_DELETED = 'deleted';
	const LOG_PURCHASE = 'purchase';
	const LOG_INSTALL = 'Install';

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getLogType($type, $all = FALSE)
	{
		$types = array(
			self::LOG_ADDED => 'Добавяне',
			self::LOG_EDITED => 'Редактиране',
			self::LOG_DELETED => 'Изтриване',
			self::LOG_INSTALL => 'Инсталиране',
		);
		if($all)
			return $types;

		if(array_key_exists($type, $types))
			return $types[$type];

		return 'Друго';
	}

	public function tableName()
	{
		return '{{logs}}';
	}

	public function rules()
	{
		return array(
			array('timestamp', 'numerical', 'integerOnly'=>true),
			array('ip, username', 'length', 'max'=>32),
			array('action', 'length', 'max'=>64),
			array('remarks', 'length', 'max'=>256),
			array('timestamp, username, action', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array();
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'timestamp' => 'Дата',
			'ip' => 'Ip',
			'username' => 'Админ',
			'action' => 'Действие',
			'remarks' => 'Подробности',
		);
	}

	public function search()
	{

		$criteria=new CDbCriteria;

		if($this->timestamp)
			$criteria->addBetweenCondition('timestamp', strtotime("{$this->timestamp} 00:00:00"), strtotime("{$this->timestamp} 23:59:59"));
		$criteria->compare('username',$this->username,true);
		$criteria->compare('action',$this->action,true);
		$criteria->order = 'id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}

	public function beforeValidate() {
		if($this->isNewRecord)
		{
			$this->timestamp = time();
			$this->username = Yii::app()->user->name;
			$this->ip = $_SERVER['REMOTE_ADDR'];
		}
		return parent::beforeValidate();
	}

	public function afterDelete() {
		Syslog::add(
			Logs::LOG_DELETED,
			'Изтрит запис от системния лог № <strong>' .
				$this->id . '</strong>, определено за админ strong>' .
				$this->username . '</strong>'
		);
		return parent::afterDelete();
	}
}