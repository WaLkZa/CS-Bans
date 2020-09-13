<?php
/**
 * Вьюшка инсталлера
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle=Yii::app()->name . ' - Обновяване';
$this->breadcrumbs=array(
	'Обновяване',
);
?>

<h2>Обновяване</h2>

<?php if(empty($_POST['license'])): ?>

	<?php echo CHtml::form(); ?>

	<p><label class="checkbox"><?php echo CHtml::checkBox('license'); ?> Приемам условията <?php
			echo CHtml::link('лицензионно споразумение', array('/site/license'), array('target' => '_blank')) ?></label></p>

	<?php echo CHtml::submitButton('Обнови', array('class' => 'btn btn-primary')); ?><br>

	<?php echo CHtml::endForm(); ?>

<?php else: ?>

	<div class="alert alert-success">Обновяването е успешно!</div>

<?php endif; ?>