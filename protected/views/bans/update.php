<?php
/**
 * Вьюшка редактирования бана
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Редактиране на баннат играч ' . $model->player_nick;
$this->breadcrumbs=array(
	'Бан листа'=>array('index'),
	'Бан №'.$model->bid=>array('view','id'=>$model->bid),
	'Редактиране',
);

?>

<h2>Редактиране на бан №<?php echo $model->bid; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>