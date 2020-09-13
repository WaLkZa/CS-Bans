<?php
/**
 * Вьюшка просмотра деталей бана
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$page = 'Бан листа';
$this->pageTitle = Yii::app()->name . ' - ' . $page . ' - Детайли относно бана ' . $model->player_nick;
$this->breadcrumbs=array(
	$page=>array('index'),
	$model->player_nick,
);
if($geo) {
	Yii::app()->clientScript->registerScriptFile('//api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU',CClientScript::POS_END);
	Yii::app()->clientScript->registerScript('yandexmap', "
		ymaps.ready(inityamaps);
		function inityamaps () {
			var myMap = new ymaps.Map('map', {center: [{$geo['lat']}, {$geo['lng']}], zoom: 10});
		}
	",CClientScript::POS_END);
}

if($model->ban_length == '-1') {
    $length = 'Ънбаннат';
} else {
    $length = Prefs::date2word($model->ban_length);
    if($model->unbanned) {
        $length .= '(Изтекъл)';
    } elseif(Yii::app()->hasModule('billing')) {
        $length .= CHtml::link(
            'Купи ънбан',
			array('/billing/unban', 'id' => $model->primaryKey),
			array('class' => 'btn btn-mini btn-success pull-right')
        );
    }
}
?>

<h2>Подробности за бана <i><?php echo CHtml::encode($model->player_nick); ?></i></h2>
<div style="float: right">
	<?php
	if(Webadmins::checkAccess('bans_edit', $model->admin_nick)):
	echo CHtml::link(
		'<i class="icon-edit"></i>',
		$this->createUrl('/bans/update', array('id' => $model->bid)),
		array(
			'rel' => 'tooltip',
			'title' => 'Промени',
		)
	);
	endif;
	?>
	&nbsp;
	<?php
	if(Webadmins::checkAccess('bans_unban', $model->admin_nick) && !$model->unbanned):
	echo CHtml::ajaxLink(
		'<i class="icon-remove"></i>',
		$this->createUrl('/bans/unban', array('id' => $model->bid)),
		array(
			'type' => 'post',
			'beforeSend' => 'function() {if(!confirm("Премахни бана на '.$model->player_nick.'?")) {return false;} }',
			'success' => 'function(data) {alert(data); document.location.href="'.$this->createUrl('/bans/index').'";}'
		),
		array(
			'rel' => 'tooltip',
			'title' => 'Ънбанни',
		)
	);
	endif;
	?>
	&nbsp;
	<?php
	if(Webadmins::checkAccess('bans_delete', $model->admin_nick)):
	echo CHtml::ajaxLink(
		'<i class="icon-trash"></i>',
		$this->createUrl('/bans/delete', array('id' => $model->bid, 'ajax' => 1)),
		array(
			'type' => 'post',
			'beforeSend' => 'function() {if(!confirm("Сигурен ли си, че искаш да изтриеш бана?")) {return false;} }',
			'success' => 'function() {alert("Банът е изтрит."); document.location.href="'.$this->createUrl('/bans/index').'"}'
		),
		array(
			'rel' => 'tooltip',
			'title' => 'Изтриване на бан',
		)
	);
	endif;
	?>
</div>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'type' => array('condensed', 'bordered'),
	'htmlOptions' => array('style'=>'text-align: left'),
	'attributes'=>array(
		array(
			'name' => 'player_ip',
			'type' => 'raw',
			'value' => $geo['city'] ? CHtml::link(
					$model->player_ip,
					'#',
					array(
						'onclick' => '$("#modal-map").modal("show");',
						'rel' => 'tooltip',
						'title' => 'Подробности за IP адреса'
					)
				) : $model->player_ip,
			'visible' => ($ipaccess)
		),
		array(
			'name' => 'player_id',
			'type' => 'raw',
			'value' => Prefs::steam_convert($model->player_id, TRUE)
				? CHtml::link($model->player_id, 'http://steamcommunity.com/profiles/'
						. Prefs::steam_convert($model->player_id), array('target' => '_blank'))
				: $model->player_id,
		),
		'player_nick',
		'adminName:html',
		'ban_reason',
		array(
			'name' => 'ban_created',
			'value' => date('d.m.Y - H:i:s', $model->ban_created),
		),
		array(
			'name' => 'ban_length',
			'type' => 'raw',
			'value' => $length
		),
		'expiredTime',
		'server_name',
		'ban_kicks',
	),
)); ?>

<hr>
<p class="text-success">
	<i class="icon-calendar"></i>
	Всички банове на играча
</p>
<?php
$this->widget('bootstrap.widgets.TbGridView',array(
	'type' => 'bordered stripped',
	'id'=>'ban-history-grid',
	'dataProvider'=>$history,
	'enableSorting' => FALSE,
	'template' => '{items} {pager}',
	'columns'=>array(
		array(
			'name' => 'player_nick',
			'type' => 'html',
			'value' => 'Chtml::link($data->player_nick, Yii::app()->createUrl("/bans/view", array("id" => $data->bid)))'
		),
		array(
			'name' => 'player_id',
			'type' => 'raw',
			'value' => 'Prefs::steam_convert($data->player_id, TRUE)
				? CHtml::link($data->player_id, "http://steamcommunity.com/profiles/"
						. Prefs::steam_convert($data->player_id), array("target" => "_blank"))
				: $data->player_id',
		),
		array(
			'name' => 'player_ip',
			'value' => '$data->player_ip',
			'visible' => $ipaccess
		),
		array(
			'name' => 'ban_created',
			'value' => 'date("d.m.Y - H:i:s", $data->ban_created)',
		),
		'ban_reason',
		
		array(
			'name' => 'ban_length',
			'type' => 'raw',
			'value' =>
				'$data->ban_length == "-1"
					?
				"Разбанен"
					:
				Prefs::date2word($data->ban_length) .
				($data->expired == 1 ? " (изтекъл)" : "")'
		),
	),
));
?>
<hr>
<p class="text-success">
	<i class="icon-comment"></i>
	Коментари
</p>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped bordered condensed',
	'id'=>'comments-grid',
	'template' => '{items}',
	'dataProvider'=> $c,
	'enableSorting' => FALSE,
	'rowHtmlOptionsExpression' => 'array(
		"id" => "$data->id"
	)',
	'columns'=>array(

		array(
			'header' => 'Дата',
			'value'=>'date("d.m.Y", $data->date)',
			'htmlOptions' => array(
				'style' => 'width:80px'
			)
		),

		array(
			'header' => 'Коментар',
			'value'=>'$data->comment',
		),

		'name',
		array(
			'name' => 'addr',
			'value' => '$data->addr',
			'htmlOptions' => array(
				'style' => 'width:100px'
			),
			'visible' => $ipaccess
		),
		array(
			'name' => 'email',
			'value' => '$data->email',
			'htmlOptions' => array(
				'style' => 'width:200px'
			)
		),
		//'email',

		array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
			'header' => 'Действие',
            'template'=>'{update} {delete}',
            'buttons'=>array
            (
                'delete' => array
                (
                    'label'=>'Изтрий',
                    'icon'=>'trash',
                    'url'=>'Yii::app()->createUrl("/comments/delete", array("id"=>$data->id))',
                ),
				'update' => array
                (
                    'label'=>'Редактирай',
                    'icon'=>'pencil',
                    'url'=>'Yii::app()->createUrl("/comments/update", array("id"=>$data->id, "bid" => $data->bid))',
                ),

            ),
            'htmlOptions'=>array(
                'style'=>'width: 80px; text-align:center',
            ),
			'visible' => Webadmins::checkAccess('bans_edit', $model->admin_nick)
        )

	),
));

if(Yii::app()->config->use_comment && (!Yii::app()->user->isGuest || Yii::app()->config->comment_all)):?>
	<div style="width: auto; margin: 0 auto">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label'=>'Добави коментар',
			'buttonType' => 'button',
			'size'=>'small', // null, 'large', 'small' or 'mini'
			'htmlOptions' => array('onclick' => '$("#addcomment").slideToggle("slow");'),
		)); ?>
	</div>
	<div style="width: 100%; display: none" id="addcomment">
		<?php echo CHtml::form('','post'); ?>
		<?php echo CHtml::errorSummary($comments); ?>
		<table class="table table-bordered">
			<tr>
				<td class="span4">
					<?php echo CHtml::activeLabel(
							$comments,
							'email'
						); ?>
				</td>
				<td class="span8">
					<?php
					echo CHtml::activeEmailField(
							$comments,
							'email',
							!Yii::app()->user->isGuest ?
							array(
								'value' => Yii::app()->user->email,
								'readonly' => 'readonly'
							)
							:
							''
						)
					?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($comments, 'name'); ?>
				</td>
				<td>
					<?php
					echo CHtml::activeTextField(
							$comments,
							'name',
							!Yii::app()->user->isGuest ?
							array(
								'value' => Yii::app()->user->name,
								'readonly' => 'readonly'
							)
							:
							''
						)
					?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($comments, 'comment'); ?>
				</td>
				<td>
					<?php
					echo CHtml::activeTextArea($comments, 'comment')
					?>
				</td>
			</tr>
			<?php if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest):?>
			<tr>
				<td>
					<?php echo CHtml::activeLabelEx($comments, 'verifyCode')?>
				</td>
				<td>
					<?php echo CHtml::activeTextField($comments, 'verifyCode')?>
					<?php $this->widget('CCaptcha')?>
				</td>
			</tr>
			<?php endif?>
			<tr>
				<td colspan="2">
					<?php echo CHtml::submitButton($label = 'Запази'); ?>
				</td>
			</tr>
		</table>
		<?php echo CHtml::endForm(); ?>
	</div>
<?php endif?>
<hr />
<p class="text-success">
	<i class="icon-folder-open"></i>
	Файлове
</p>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped bordered condensed',
	'id'=>'files-grid',
	'template' => '{items}',
	'dataProvider'=>$f,
	'enableSorting' => FALSE,
	'columns'=>array(

		array(
			'header' => 'Дата',
			'value'=>'date("d.m.Y", $data->upload_time)',
		),

		'demo_real',

		array(
			'header' => 'Размер',
			'value'=>'Prefs::formatfilesize($data->file_size)',
		),

		array(
			'header' => 'Коментари',
			'value'=>'$data->comment',
		),

		'name',
		array(
			'name' => 'addr',
			'value' => '$data->addr',
			'visible' => $ipaccess
		),
		'down_count',

		array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
			'header' => 'Действия',
            'template'=>'{download} {update} {delete}',
            'buttons'=>array
            (
                'download' => array
                (
                    'label'=>'Изтегли',
                    'icon'=>'download-alt',
                    'url'=>'Yii::app()->createUrl("/files/download", array("id"=>$data->id))',
                ),
                'update' => array
                (
                    'label'=>'Редактирай',
                    'icon'=>'pencil',
                    'url'=>'Yii::app()->createUrl("/files/update", array("id"=>$data->id))',
					'visible' => 'Webadmins::checkAccess(\'bans_edit\', $data->name)'
                ),
				'delete' => array
                (
                    'label'=>'Изтрий',
                    'icon'=>'trash',
                    'url'=>'Yii::app()->createUrl("/files/delete", array("id"=>$data->id, "YII_CSRF_TOKEN" => Yii::app()->request->csrfToken))',
					'visible' => 'Webadmins::checkAccess(\'bans_edit\', $data->name)'
                ),
            ),
            'htmlOptions'=>array(
                'style'=>'width: 120px; text-align:center',
            ),
        )

	),
));
?>
<?php if(Yii::app()->config->use_demo && (!Yii::app()->user->isGuest || Yii::app()->config->demo_all)):?>
	<div style="width: auto; margin: 0 auto">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label'=>'Добави файл',
			'buttonType' => 'button',
			'size'=>'small', // null, 'large', 'small' or 'mini'
			'htmlOptions' => array('onclick' => '$(".addfile").slideToggle("slow");'),
		)); ?>
	</div>
	<div style="width: 100%; display: none; margin: 0 auto" class="addfile">
		<?php echo CHtml::form('','post', array('id' => 'addfile-form', 'enctype'=>'multipart/form-data')); ?>
		<?php echo CHtml::errorSummary($files); ?>
		<table class="table table-bordered">
			<tr>
				<td class="span4">
					<?php echo CHtml::activeLabel(
							$files,
							'email'
						); ?>
				</td>
				<td class="span8">
					<?php
					echo CHtml::activeEmailField(
							$files,
							'email',
							!Yii::app()->user->isGuest ?
							array(
								'value' => Yii::app()->user->email,
								'readonly' => 'readonly'
							)
							:
							''
						)
					?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($files, 'name'); ?>
				</td>
				<td>
					<?php
					echo CHtml::activeTextField(
							$files,
							'name',
							!Yii::app()->user->isGuest ?
							array(
								'value' => Yii::app()->user->name,
								'readonly' => 'readonly'
							)
							:
							''
						)
					?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($files, 'demo_real'); ?>
				</td>
				<td>
					<?php echo CHtml::activeFileField($files, 'demo_real') ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($files, 'comment'); ?>
				</td>
				<td>
					<?php echo CHtml::activeTextArea($files, 'comment'); ?>
				</td>
			</tr>
			<?php if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest):?>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($files, 'verifyCode'); ?>
				</td>
				<td>
					<?php echo CHtml::activeTextField($files, 'verifyCode') ?>
					<?php $this->widget('CCaptcha')?>
				</td>
			</tr>
			<?php endif?>
			<tr>
				<td colspan="2">
					<?php echo CHtml::submitButton('Запази'); ?>
				</td>
			</tr>
		</table>
		<?php echo CHtml::endForm(); ?>
	</div>
<?php endif;?>
<?php if($ipaccess): ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal',
	array(
		'id'=>'modal-map',
		'htmlOptions'=> array('style'=>' width:860px; margin-left: -430px;height: 600px'),
)); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
	<h3>Информация за IP "<?php echo $model->player_ip ?>"</h3>
</div>
<div class="modal-body" style="min-height: 460px">
	<div id="map" style="width:800px; height:400px; marg: 0 auto"></div>
	<div style="top: -30px">
		<b>Страна: </b>
		<?php echo $geo['country'] ?>
		<br>
		<b>Област: </b>
		<?php echo $geo['region'] ?>
		<br>
		<b>Град: </b>
		<?php echo $geo['city'] ?>
	</div>
</div>
<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Затвори',
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal'),
    )); ?>
</div>
<?php $this->endWidget(); ?>
<?php endif; ?>
