<?php
/**
 * Вьюшка управления веб админами
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - уеб админи';
$this->breadcrumbs=array(
	'Админ панел'=>array('/admin/index'),
	'Управление на уеб админи',
);

$this->menu=array(
	array('label'=>'Админ панел', 'url'=>array('/admin/index')),
	array('label'=>'Добави админ', 'url'=>array('create')),
);

$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webadmins'));
?>

<h2>Управление на уеб админи</h2>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'webadmins-grid',
	'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'username',
		'email',
		array(
			'name' => 'level',
			'filter' => Levels::getList(),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
