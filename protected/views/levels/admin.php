<?php
/**
 * Вьюшка управления уровнями веб админов
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
	'Уеб права',
);

$this->menu=array(
	array('label'=>'Админ панел', 'url'=>array('/admin/index')),
	array('label'=>'Добави права', 'url'=>array('create')),
);
$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webadmlevel'));
?>

<h2>Управление на правата на уеб админи</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'levels-grid',
	'dataProvider'=>$model->search(),
	'enableSorting' => FALSE,
	'columns'=>array(
		'level',

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update} {delete}'
		),
	),
)); ?>
