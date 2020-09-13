<?php
/**
 * Вьюшка просмотра деталей ссылки главного меню
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
	'Админ панел'=> array('/admin/index'),
	'Главно меню'=>array('admin'),
	'Линк № '.$model->id,
);

$this->menu=array(
	array('label'=>'Изтрий','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Изтриване на линка?')),
	array('label'=>'Редактирай','url'=>array('update','id'=>$model->id)),
	array('label'=>'Добави линк','url'=>array('create')),
	array('label'=>'Управление на линкове','url'=>array('admin')),
);
$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webmainmenu'));
?>

<h2>Детайли за линк №<?php echo $model->id; ?></h2>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'pos',
		array(
			'name' => 'activ',
			'value' => $model->activ == 1 ? 'Да' : 'Не'
		),
		'lang_key',
		'url',
		'lang_key2',
		'url2',
	),
)); ?>
