<?php
/**
 * Вьюшка редактирования сервера
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Редактиране на сървър ' . $model->hostname;

$this->breadcrumbs=array(
	'Админ панел'=>array('/admin/index'),
	'Сървъри'=>array('admin'),
	'Редактиране на сървър ' . $model->hostname,
);

$this->renderPartial('/admin/mainmenu', array('active' =>'server', 'activebtn' => 'servsettings'));

$this->menu = array(
	array(
		'label'=>'Админ панел',
		'url'=>array('/admin/index'),
	),
	array(
		'label'=>'Сървъри',
		'url'=>array('/serverinfo/admin'),
	),
);
?>

<h2>Редактиране на сървър №<?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'timezones' => $timezones)); ?>