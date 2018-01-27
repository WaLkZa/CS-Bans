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
 * Модель для таблицы "{{amxadmins}}".
 *
 * Доступные поля таблицы '{{amxadmins}}':
 * @property integer $id ID админа
 * @property string $username имя админа
 * @property string $password Пароль админа
 * @property string $access Доступ
 * @property string $flags Флаги
 * @property string $steamid Стим
 * @property string $nickname Ник
 * @property integer $icq Контакты
 * @property integer $ashow Показывать ли на странице админов
 * @property integer $created Дата добавления
 * @property integer $expired Дата окончания
 * @property integer $days Дней админки
 */
class Amxadmins extends CActiveRecord
{
	//public $accessflags = array();
	public $change;
	public $addtake = null;
	//public $servers;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{amxadmins}}';
	}


	public function getAccessflags() {

		return str_split($this->access);
	}

	public function setAccessflags($value) {
		//return false;
	}

	public function scopes()
    {
        return array(
            'sort'=>array(
                'order'=>'`expired` ASC, `nickname` ASC'
            ),
        );
    }

	public function rules()
	{
		return array(
			array('steamid, nickname', 'required'),
			array('accessflags, addtake, servers', 'safe'),
			array('icq, ashow, days, change', 'numerical', 'integerOnly'=>true),
			array('username, access, flags, steamid, nickname', 'length', 'max'=>32),
			array('password', 'length', 'max'=>50),
			array('id, username, password, access, flags, steamid, nickname, icq, ashow, created, expired, days', 'safe',  'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'servers' => array(
				self::MANY_MANY,
				'Serverinfo',
				'{{admins_servers}}(admin_id, server_id)'
			),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'SteamID',
			'password' => 'Парола',
			'access' => 'Флагове за достъп',
			'accessflags' => 'Флагове за достъп',
			'flags' => 'Тип админ',
			'steamid' => 'Steamid/IP/Ник',
			'nickname' => 'Ник',
			'icq' => 'ICQ',
			'ashow' => 'Показване в списъка с админи',
			'created' => 'Дата на добавяне',
			'expired' => 'Дата на изтичане',
			'days' => 'Дни',
			'long' => 'Оставащи дни',
			'change' => 'Нов срок',
			'addtake' => 'Избор',
			'servers' => 'Добави в сървъра',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('access',$this->access,true);
		$criteria->compare('flags',$this->flags,true);
		$criteria->compare('steamid',$this->steamid,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('icq',$this->icq);
		$criteria->compare('ashow',$this->ashow);
		$criteria->compare('created',$this->created);
		$criteria->compare('expired',$this->expired);
		$criteria->compare('days',$this->days);
		//$criteria->order = 'nickname ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page,
			),
		));
	}

	public static function getFlags($adminlist = false)
	{
		if($adminlist)
		{
			return array(
				'a' => 'Имунитет (не може да бъде кикван/банван и т.н)',
				'b' => 'Разервиран слот',
				'c' => 'Команда amx_kick',
				'd' => 'Команда amx_ban и amx_unban',
				'e' => 'Команда amx_slay и amx_slap',
				'f' => 'Команда amx_map',
				'g' => 'Команда amx_cvar (не всички кварове ще бъдат достъпни)',
				'h' => 'Команда amx_cfg',
				'i' => 'amx_chat и други чат команди',
				'j' => 'amx_vote и други команди за гласуване',
				'k' => 'Достъп до sv_password (чрез amx_cvar командата)',
				'l' => 'Достъп до amx_rcon и rcon_password (чрез amx_cvar командата)',
				'm' => 'Персонално ниво на достъп A (за допълнителни плъгини)',
				'n' => 'Персонално ниво на достъп B',
				'o' => 'Персонално ниво на достъп C',
				'p' => 'Персонално ниво на достъп D',
				'q' => 'Персонално ниво на достъп E',
				'r' => 'Персонално ниво на достъп F',
				's' => 'Персонално ниво на достъп G',
				't' => 'Персонално ниво на достъп H',
				'u' => 'Достъп до админ менюто',
				'z' => 'Обикновен потребител (без администраторски права)'
			);
		}

		return array(
			'a' => '[a] Имунитет (не може да бъде кикван/банван и т.н)',
			'b' => '[b] Разервиран слот',
			'c' => '[c] Команда amx_kick',
			'd' => '[d] Команда amx_ban и amx_unban',
			'e' => '[e] Команда amx_slay и amx_slap',
			'f' => '[f] Команда amx_map',
			'g' => '[g] Команда amx_cvar (не всички кварове ще бъдат достъпни)',
			'h' => '[h] Команда amx_cfg',
			'i' => '[i] amx_chat и други чат команди',
			'j' => '[j] amx_vote и други команди за гласуване',
			'k' => '[k] Достъп до sv_password (чрез amx_cvar командата)',
			'l' => '[l] Достъп до amx_rcon и rcon_password (чрез amx_cvar командата)',
			'm' => '[m] Персонално ниво на достъп A (за допълнителни плъгини)',
			'n' => '[n] Персонално ниво на достъп B',
			'o' => '[o] Персонално ниво на достъп C',
			'p' => '[p] Персонално ниво на достъп D',
			'q' => '[q] Персонално ниво на достъп E',
			'r' => '[r] Персонално ниво на достъп F',
			's' => '[s] Персонално ниво на достъп G',
			't' => '[t] Персонално ниво на достъп H',
			'u' => '[u] Достъп до админ менюто',
			'z' => '[z] Обикновен потребител (без администраторски права)'
		);
	}

	protected function beforeDelete() {
		parent::beforeDelete();
		$servers = AdminsServers::model()->findByAttributes(array('admin_id' => $this->id));
		if ($servers !== null) {
            $servers->deleteAllByAttributes(array('admin_id' => $this->id));
        }

        return true;
	}

	protected function beforeSave() {
        $removePwd = filter_input(INPUT_POST, 'removePwd', FILTER_VALIDATE_BOOLEAN);
        if($removePwd) {
            $this->password = '';
        }
        
		if($this->isNewRecord) {
			$this->created = time();
            if($this->password && $this->scenario != 'buy') {
                $this->password = md5($this->password);
            }
            if($this->flags != 'a' && !$this->password) {
                $this->flags .= 'e';
            }
			$this->expired = $this->days != 0 ? ($this->days * 86400) + time() : 0;
		} else {
			if ($this->password) {
                $this->password = md5($this->password);
            } else {
                $oldadmin = Amxadmins::model()->findByPk($this->id);
                if ($oldadmin->password && !$removePwd) {
                    $this->password = $oldadmin->password;
                } elseif($this->flags != 'a') {
                    $this->flags .= 'e';
                }
            }

            if($this->expired == 0) {
				$this->expired = time();
			}

			switch($this->addtake) {
				case '1':
					$this->expired = $this->expired - ($this->change *86400);
					$this->days = $this->days - $this->change;
					break;
				case '0':
					$this->expired = $this->expired + ($this->change *86400);
					$this->days = $this->days + $this->change;
					break;
				default:
					$this->expired = 0;
					$this->days = 0;
			}
		}
		return parent::beforeSave();
	}

	protected function afterValidate() {
		
		if ($this->scenario == 'buy') {
            return true;
        }

        if (!$this->access) {
            $this->addError('access', 'Изберете флаговете за достъп');
        }

        if($this->isNewRecord && $this->flags === 'a' && !$this->password) {
            $this->addError('password', 'За админ по ник задължително трябва да зададете парола');
        }
        
		if ($this->flags === 'd' && !filter_var($this->steamid, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4))) {
            $this->addError('steamid', 'Неправилно въведен IP');
        }

        if ($this->flags === 'c' && !Prefs::validate_value($this->steamid, 'steamid')) {
            $this->addError('steamid', 'Неправилно въведен SteamID');
        }

        if ($this->password && !preg_match('#^([a-z0-9]+)$#i', $this->password)) {
			$this->addError ('password', 'Паролата може да бъде само на латиница');
		}
        
        if(!$this->isNewRecord && $this->days < $this->change && $this->addtake === '1')
		{
			$this->addError ('', 'Грешка! Не може да премахнете повече дни от текущите');
		}

        if(empty($this->servers)) {
            $this->addError ('servers', 'Изберете поне един сървър');
        }
        
        if($this->hasErrors()) {
            return $this->getErrors();
        }
        
		return parent::afterValidate();
	}

	public static function getAuthType($get = false)
	{
		$flags = array(
			'a' => 'Ник',
			'c' => 'SteamID',
			'd' => 'IP'
		);
		if($get) {
            $flag = $get{0};
			if(isset($flags[$flag])) {
                $return = $flags[$flag];
                if(!isset($get{1})) {
                    $return .= ' + пароль';
                }
				return $return;
			}
			return 'Неизвестно';
		}
		return $flags;
	}

	public function getLong()
	{
		$long = $this->expired - time();
		if ($this->expired == 0 || $long < 0) {
            return false;
        }

        return intval($long / 86400);
	}

	public function afterSave() {

		if(!empty($this->servers) && $this->isNewRecord) {
			foreach($this->servers as $is) {
				$inservers = new AdminsServers;
				$inservers->unsetAttributes();
				if (!Serverinfo::model()->findByPk($is)) {
                    continue;
                }

                $inservers->admin_id = $this->id;
				$inservers->server_id = intval($is);
				$inservers->use_static_bantime = 'no';
				if (!$inservers->save()) {
                    continue;
                }
            }
		}

		if ($this->isNewRecord) {
            Syslog::add(Logs::LOG_ADDED, 'Добавен е нов AmxModX админ <strong>' . $this->nickname . '</strong>');
        } else {
            Syslog::add(Logs::LOG_EDITED, 'Промена информация за AmxModX админа <strong>' . $this->nickname . '</strong>');
        }
        return parent::afterSave();
	}

	public function afterDelete() {
        AdminsServers::model()->deleteAllByAttributes(array('admin_id' => $this->id));
		Syslog::add(Logs::LOG_DELETED, 'Изтрит е AmxModX админ <strong>' . $this->nickname . '</strong>');
		return parent::afterDelete();
	}
}