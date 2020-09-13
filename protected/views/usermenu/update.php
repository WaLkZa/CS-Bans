<?php
/**
 * Вьюшка редактирования ссылки главного меню
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Редактиране на линк ';
$this->breadcrumbs=array(
	'Админ панел'=> array('/admin/index'),
	'Главно меню'=>array('admin'),
	'Линк № '.$model->id=>array('view','id'=>$model->id),
	'Редактирай',
);

$this->menu=array(
	array('label'=>'Добави линк','url'=>array('create')),
	array('label'=>'Управление на линкове','url'=>array('admin')),
);
$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webmainmenu'));
?>

<h2>Редактирай линк № <?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>