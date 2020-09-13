<?php
/**
 * Вьюшка редактирования уровня веб админов
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Уеб права';

$this->breadcrumbs=array(
	'Админ панел'=>array('/admin/index'),
	'Уеб права'=>array('admin'),
	'Редактиране на права #' . $model->level
);

$this->menu=array(
	array('label'=>'Админ панел','url'=>array('index')),
	array('label'=>'Права','url'=>array('admin')),
);

$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webadmlevel'));
?>

<h2>Редактиране на права #<?php echo $model->level; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>