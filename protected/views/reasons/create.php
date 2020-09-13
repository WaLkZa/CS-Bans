<?php
/**
 * Вьюшка добавления причины бана
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$page = 'Причини за бан :: Добави причина';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	'Админ панел'=>array('/admin/index'),
	'Причини за бан' => array('/admin/reasons'),
	'Добави причина'
);

$this->renderPartial('/admin/mainmenu', array('active' =>'server', 'activebtn' => 'servreasons'));

?>

<h2>Добави причина за бан</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>