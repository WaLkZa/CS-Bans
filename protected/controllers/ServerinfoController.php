<?php

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Всички права запазени.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */
class ServerinfoController extends Controller
{

	public $layout='//layouts/main';

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
			'ajaxOnly + context, serverdetail, getinfo, sendcommand',
		);
	}

	public function actionSendcommand($id)
	{
		if(!Webadmins::checkAccess('servers_edit'))
			throw new CHttpException(403, 'Нямате достатъчно права');

		if(isset($_POST['command']))
		{
			$server = $this->loadModel($id);

			$response = $server->RconCommand(CHtml::encode($_POST['command']));

			if($_POST['command'] == 'amx_reloadadmins' && $server->RconCommand('echo Hi') === 'Hi')
				$response = 'Списъка с админите е обновен';

			if($response)
				$return = CHtml::encode ($response);

			else
				$return = 'Грешка при изпращане на командата';

			Yii::app()->end(CHtml::encode($response));
		}
	}

	/**
	 * Вывод информации о серверах аяксом.
	 * @param integer|false $limit Количество вывода серверов
	 * @return string Возвращает Javascript для аякса
	 */
	public function actionGetinfo()
	{
		
		$id = filter_input(INPUT_POST, 'server');
		$server = $this->loadModel($id);
		
		if($server === NULL)
			Yii::app()->end();

		Yii::app()->end(json_encode($server->getInfo()));
	}

	/**
	 * Экшн для Ajax запросов действий над игроком
	 * @param intval $id ID сервера
	 * @param string $action Действие (Бан, кик, сообщение, профиль)
	 * @return false
	 */
	public function actionContext($id)
	{
		if(!Webadmins::checkAccess('servers_edit'))
			throw new CHttpException(403, 'Нямате достатъчно права');

		// Проверяем права
		if(!Webadmins::checkAccess('bans_add'))
			throw new CHttpException(403, "Нямате достатъчно права");

		// Проверочки
		$action = CHtml::encode($_POST['action']);
		$player = CHtml::encode($_POST['player']);
		$reason = CHtml::encode($_POST['reason']);

		switch($action)
		{
			case 'ban':
				$command = 'amx_ban '.  intval($_POST['time']).' '.$player.' ' . $reason;
				break;
			case 'kick':
				$command = 'amx_kick '.$player;
				break;
			case 'message':
				if(!preg_match('#^[\w ]+$#i', $reason))
					Yii::app()->end("$('#loading').hide();alert('Само латински букви и цифри');");
				$command = 'amx_psay "' . $player . '" "' . $reason . '"';
				break;
		}

		$server = Serverinfo::model()->findByPk(intval($id));
		$return = $server->RconCommand($command);
		if($server->RconCommand($command))
			$return = 'Командата е изпратена успешно';

		else
			$return = 'Грешка при изпращане на командата';

		Yii::app()->end("$('#loading').hide();alert('$return');");
	}

	/**
	 * Вывод инфы о сервере
	 * @param integer $id ID сервера
	 */
	public function actionView($id)
	{
		// Аякс запросы
		if(Yii::app()->request->isAjaxRequest)
			$this->layout = false;

		$this->render('view',array(
			'server'=>$this->loadModel($id),
		));
	}

	/**
	 * Добавить сервер
	 */
	public function actionCreate()
	{
		// Проверка прав
		if(!Webadmins::checkAccess('servers_edit'))
			throw new CHttpException(403, "Нямате достатъчно права");

		$model=new Serverinfo;

		// $this->performAjaxValidation($model);

		if(isset($_POST['Serverinfo']))
		{
			$model->attributes=$_POST['Serverinfo'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Редактировать сервер
	 * @param integer $id ID сервера
	 */
	public function actionUpdate($id)
	{
		// Проверка прав
		if(!Webadmins::checkAccess('servers_edit'))
			throw new CHttpException(403, "Нямате достатъчно права");

		$this->layout = '//layouts/column2';
		$model=$this->loadModel($id);

		// $this->performAjaxValidation($model);

		if(isset($_POST['Serverinfo']))
		{
			$model->attributes=$_POST['Serverinfo'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
			'timezones' => $this->getTimezones(),
		));
	}

	/**
	 * Удаление сервера
	 * @param integer $id ID сервера
	 */
	public function actionDelete($id)
	{
		// Проверка прав
		if(!Webadmins::checkAccess('servers_edit'))
			throw new CHttpException(403, "Нямате достатъчно права");

		$this->loadModel($id)->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 *Главная страница серверов
	 */
	public function actionIndex()
	{

		$model=new Serverinfo('search');
		$model->unsetAttributes();
		if(isset($_GET['Serverinfo']))
			$model->attributes=$_GET['Serverinfo'];

		$allbans = Bans::model()->cache(600)->count();
		$activebans = Bans::model()->cache(600)->count('((ban_created+(ban_length*60)) > :time OR ban_length = 0) AND `expired` = 0', array(':time' => time()));
		$permbans = Bans::model()->cache(600)->count('ban_length = 0');

		$this->render('index',array(
			'servers'=>Serverinfo::model()->cache(600)->findAll(array('order' => '`hostname` ASC')),
			'info' => array(
				'bancount' => $allbans,
				'actbans' => $activebans,
				'permbans' => $permbans,
				'tempbans' => $activebans - $permbans,
				'admins' => Amxadmins::model()->cache(600)->count(),
				'serversCount' => Serverinfo::model()->cache(600)->count()
			)
		));
	}

	/**
	 * Управление серверами
	 */
	public function actionAdmin()
	{
		if(!Webadmins::checkAccess('servers_edit'))
			throw new CHttpException(403, 'Нямате достатъчно права');

		$model=new Serverinfo('search');
		$model->unsetAttributes();
		if(isset($_GET['Serverinfo']))
			$model->attributes=$_GET['Serverinfo'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Serverinfo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Заявената страница не съществува');
		return $model;
	}

	/**
	 * Вывод инфы о сервере в модальке
	 * @throws CHttpException
	 */
	public function actionServerdetail()
	{
		$model = Serverinfo::model()->findByPk($_POST['sid']);
		
		$info = $model->getInfo();

		$players = "";
		if (empty($info['playersinfo']) || !is_array($info['playersinfo'])) {
			$players .= "<table class=\"items table table-bordered table-condensed\">";
			$players .= "<tr class=\"odd\"><td width=\"100%\" style=\"text-align:center\">Няма играчи</td></tr></table>";
		} else {
			$players .= "<table class=\"items table table-bordered table-condensed\">";
			$players .= "<tr class=\"odd\">";
			$players .= "<td width=\"70%\"><b>Ник</b></td>";
			$players .= "<td><b>Резултат</b></td>";
			$players .= "<td><b>Време</b></td>";

			foreach ($info['playersinfo'] as $player_key => $player) {
				$players .= "<tr class=\"odd\">";
				$players .= "<td width=\"70%\">" . CHtml::encode($player['name']) . "</td>";
				$players .= "<td style=\"text-align:center\">" . intval($player['score'], ENT_QUOTES) . "</td>";
				$players .= "<td>" . (function_exists('query_live') ? $player['time'] : Prefs::date2word(intval($player['time']), FALSE, TRUE)) . "</td>";
				$players .= "</tr>";
			}
			$players .= "</table>";
		}

		$js = "$('#server-name').html('" . CJavaScript::quote($info['name']) . "');";
		$js .= "$('#serverlink').html('" . CJavaScript::quote($info['name']) . "').attr({'href': '".Yii::app()->createUrl('serverinfo/view', array('id'=>$model->id))."'});";
		$js .= "$('#server-address').html('" . CJavaScript::quote($model->address) . "');";
		$js .= "$('#steam-connect').attr({'href': 'steam://connect/" . CJavaScript::quote($model->address) . "'});";
		$js .= "$('#hlws-add').attr({'href': 'hlsw://" . CJavaScript::quote($model->address) . "'});";
		$js .= "$('#server-map').html('" . CJavaScript::quote($info['map']) . "');";
		$js .= "$('#server-players').html('" . $info['players'] . '/' . $info['playersmax'] . "');";
		$js .= "$('#serverinfo-players').html('" . CJavaScript::quote($players) . "');";
		$js .= "$('#server-mapimage').html('" . CJavaScript::quote($info['mapimg']) . "');";
		$js .= "$('#loading').hide();";
		$js .= "$('#ServerDetail').modal('show');";

		Yii::app()->end($js);
	}


	/**
	 * Аякс проверка формы
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='serverinfo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Часовые пояса
	 */
	public function getTimezones()
	{
		return array(
			'-12' => '-12',
			'-11' => '-11',
			'-10' => '-10',
			'-9' => '-9',
			'-8' => '-8',
			'-7' => '-7',
			'-6' => '-6',
			'-5' => '-5',
			'-4' => '-4',
			'-3' => '-3',
			'-2' => '-2',
			'-1' => '-1',
			'0' => '0',
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
			'7' => '7',
			'8' => '8',
			'9' => '9',
			'10' => '10',
			'11' => '11',
			'12' => '12',
		);
	}
}
