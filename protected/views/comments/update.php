<?php
/**
 * Вьюшка редактирования комментария
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name .' :: Админ панел - Редактиране на коментари';
$this->breadcrumbs=array(
	'Бан листа'=>array('/bans/index'),
	'Бан №'.$model->bid=>array('/bans/view','id'=>$model->bid),
	'Редактиране на коментар ' . $model->name,
);
?>

<h2>Редактиране на коментар №<?php echo $model->id; ?></h2>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'comments-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Полетата отбелязани с <span class="required">*</span> са задължителни.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>35)); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textAreaRow($model,'comment',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Запази',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
