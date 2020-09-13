<?php
/**
 * Управление AmxModX админами
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name . ' :: Админ панел - AmxModX админи';
$this->breadcrumbs = array(
	'Админ панел' => array('/admin/index'),
	'AmxModX админи'
);

$this->renderPartial('/admin/mainmenu', array('active' =>'server', 'activebtn' => 'servamxadmins'));

$this->menu=array(
	array('label'=>'Добави AmxModX админ','url'=>array('create')),
);
?>

<h2>Управление на AmxModX админи</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'type' => 'bordered stripped',
	'id'=>'amxadmins-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableSorting' => FALSE,
	'summaryText' => 'Резултати от {start} до {end} от общо {count} админа. Страница {page} от {pages}',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
	'rowHtmlOptionsExpression'=>'array(
		"class" => $data->expired != 0 && $data->expired <= time() ? "error" : ""
	)',
	'columns'=>array(
		'nickname',
		'steamid',
		'access',
		array(
			'name' => 'flags',
			'value' => 'Amxadmins::getAuthType($data->flags)',
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
