<?php
/**
 * Форма добавления/редактирования причин банов
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'reasons-form',
	'enableAjaxValidation'=>TRUE,
));
?>

	<p class="note">Полетата отбелязани с <span class="required">*</span> са задължителни.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'reason',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'static_bantime',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Създай' : 'Запази',
		)); ?>
		<?php echo CHtml::link(
				'Отмени',
				Yii::app()->createUrl('/admin/reasons'),
				array(
					'class' => 'btn btn-danger'
				)
			);
		?>
	</div>

<?php $this->endWidget(); ?>
