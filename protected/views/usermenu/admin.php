<?php
/**
 * Вьюшка управления ссылками главного меню
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Главно меню';
$this->breadcrumbs=array(
	'Админ панел' => array('/admin/index'),
	'Главно меню'
);

$this->menu=array(
	array('label'=>'Добави линк','url'=>array('create')),
);
$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webmainmenu'));
?>

<h2>Управление на линкове</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'usermenu-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'rowHtmlOptionsExpression' => 'array(
		"class" => $data->activ == 0 ? "error" : ""
	)',
	'enableSorting' => FALSE,
	'columns'=>array(
		array(
			'name' => 'pos',
			'value' => '$data->pos',
			'htmlOptions' => array(
				'style' => 'width: 50px'
			)
		),
		'lang_key',

		'lang_key2',

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
