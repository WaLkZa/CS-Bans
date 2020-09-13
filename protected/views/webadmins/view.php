<?php
/**
 * Вьюшка просмотра деталей веб админа
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Детайли за админ ' . $model->username;
$this->breadcrumbs=array(
	'Админ панел'=>array('/admin/index'),
	'Уеб админи'=>array('admin'),
	'Детайли за админ ' . $model->username,
);

$this->menu=array(
	array('label'=>'Админ панел', 'url'=>array('/admin/index')),
	array('label'=>'Управление на уеб админи', 'url'=>array('index')),
	array('label'=>'Обнови', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Изтрий', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Изтриване на уеб админа?')),
);
$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'webadmins'));
?>

<h2>Детайли за админ "<?php echo $model->username; ?>"</h2>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'level',
		'email',
		array(
			'name' => 'last_action',
			'type' => 'datetime',
			'value' => $model->last_action
		),
		'try',
	),
)); ?>
