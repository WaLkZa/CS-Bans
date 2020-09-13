<?php
/**
 * Вьюшка настроек системы
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$page = 'Настройки на системата';
$this->pageTitle = Yii::app()->name . ' :: Админ панел - ' . $page;

$this->breadcrumbs=array(
	'Админ панел' => array('/admin/index'),
	$page,
);

$this->renderPartial('/admin/mainmenu', array('active' =>'site', 'activebtn' => 'websettings'));
?>
<h2>Настройки на системата</h2>
<?php
$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'settings-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>true,
));
?>
<p class="note">Полетата отбелязани с <span class="required">*</span> са задължителни.</p>
<fieldset>
	<legend>Система</legend>
	<?php echo $form->errorSummary($model); ?>
	<?php //echo $form->dropDownListRow($model, 'default_lang', array('ru' => 'Русский', 'en' => 'English')); ?>
	<?php //echo $form->checkBoxRow($model, 'use_capture', array('1' => 'Да', '0' => 'Нет')); ?>
	<?php echo $form->checkBoxRow($model, 'auto_prune'); ?>
	<?php echo $form->textFieldRow($model, 'max_file_size'); ?>
	<?php echo $form->textFieldRow($model, 'cookie'); ?>
	<?php echo $form->textFieldRow($model, 'max_offences'); ?>
	<?php echo $form->textFieldRow($model, 'max_offences_reason'); ?>
	<legend>Вид</legend>
	<?php //echo $form->dropDownListRow($model, 'banner', array('---' => '---', 'amxbans.png' => 'amxbans.png')); ?>
	<?php //echo $form->textFieldRow($model, 'banner_url'); ?>
	<?php echo $form->dropDownListRow($model, 'design', $themes); ?>
	<?php echo $form->dropDownListRow($model, 'start_page', array(
		'/site/index' => 'Начало',
		'/bans/index' => 'Бан листа',
		'/serverinfo/index' => 'Сървъри',
		'/amxadmins/index' => 'Админи',
	));
	?>
	<legend>Коментари</legend>
	<?php echo $form->checkBoxRow($model, 'use_comment'); ?>
	<?php echo $form->checkBoxRow($model, 'comment_all'); ?>
	<legend>Файлове</legend>
	<?php echo $form->checkBoxRow($model, 'use_demo'); ?>
	<?php echo $form->checkBoxRow($model, 'demo_all'); ?>
	<?php echo $form->textFieldRow($model, 'file_type'); ?>
	<legend>Бан листа</legend>
	<?php echo $form->textFieldRow($model, 'bans_per_page'); ?>
	<?php echo $form->checkBoxRow($model, 'show_comment_count'); ?>
	<?php echo $form->checkBoxRow($model, 'show_demo_count'); ?>
	<?php echo $form->checkBoxRow($model, 'show_kick_count'); ?>
</fieldset>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Запазване')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Нулиране')); ?>
</div>

<?php $this->endWidget(); ?>
