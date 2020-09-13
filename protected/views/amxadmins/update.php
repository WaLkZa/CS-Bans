<?php
/**
 * Вьюшка редактирования админа серверов
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name . ' :: Админ панел - Редактиране на админ';
$this->breadcrumbs = array(
	'Админ панел' => array('/admin/index'),
	'AmxModX админи' => array('admin'),
	'Редактиране на админ ' . $model->nickname
);

$this->renderPartial('/admin/mainmenu', array('active' =>'server', 'activebtn' => 'servamxadmins'));

$this->menu=array(
	array('label'=>'Добави AmxModX админ', 'url'=>array('create')),
	array('label'=>'Управление на AmxModX админи', 'url'=>array('admin')),
);
?>

<h2>Редактиране на AmxModX админ <?php echo $model->nickname; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>