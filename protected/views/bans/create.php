<?php
/**
 * Вьюшка добавления бана
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name . ' :: Админ панел - Добавяне на бан';
$this->breadcrumbs = array(
	'Админ панел' => array('/admin/index'),
	'Добави бан'
);

$this->renderPartial('/admin/mainmenu', array('active' =>'main', 'activebtn' => 'admaddban'));

?>

<h2>Добави бан</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'activebtn' => 'admaddban')); ?>
