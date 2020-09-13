<?php
/**
 * Вьюшка добавления веб админа
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Добави уеб админ';
$this->breadcrumbs=array(
	'Админ панел'=>array('/admin/index'),
	'Уеб админи'=>array('admin'),
	'Добавяне на нов уеб админ',
);

$this->menu=array(
	array('label'=>'Админ панел', 'url'=>array('/admin/index')),
	array('label'=>'Уеб админи', 'url'=>array('admin')),
);
$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webadmins'));
?>

<h2>Добави уеб админ</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>