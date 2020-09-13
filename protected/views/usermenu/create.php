<?php
/**
 * Вьюшка добавления ссылки главного меню
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Добавяне на линк';
$this->breadcrumbs=array(
	'Админ панел'=> array('/admin/index'),
	'Главно меню'=>array('index'),
	'Добавяне на линк',
);

$this->menu=array(
	array('label'=>'Управление на линкове','url'=>array('admin')),
);
$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webmainmenu'));
?>

<h2>Добавяне на линк</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
