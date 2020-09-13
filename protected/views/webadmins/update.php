<?php
/**
 * Вьюшка редактирования веб админа
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Редактиране на уеб админ' . $model->username;
$this->breadcrumbs=array(
	'Админ панел'=>array('/admin/index'),
	'Уеб админи'=>array('admin'),
	'Редактиране на уеб админ ' . $model->username,
);

$this->menu=array(
	array('label'=>'Админ панел', 'url'=>array('/admin/index')),
	array('label'=>'Управление на уеб админи', 'url'=>array('admin')),
	array('label'=>'Изтрий', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Изтриване?')),
);
$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webadmins'));
?>

<h2>Редактиране на уеб админ "<?php echo $model->username; ?>"</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>