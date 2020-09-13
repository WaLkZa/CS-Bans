<?php
/**
 * Вьюшка просмотра сервера
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$info = $server->getInfo();

$this->pageTitle = Yii::app()->name .' :: Сървър ' . $info['name'];

$this->breadcrumbs=array(
	'Сървъри'=>array('index'),
	$info['name'],
);

// Если страницу запрашивает аякс, то не отдаем ему жабаскрипт совсем
if(!Yii::app()->request->isAjaxRequest):

if(!Yii::app()->user->isGuest)
{
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.contextmenu.js');
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.contextmenu.css');
	Yii::app()->clientScript->registerScript('playerAction', "
	setInterval('reloadplayers()', 5000);
	function reloadplayers()
	{
		$.post('',{'".Yii::app()->request->csrfTokenName."': '".Yii::app()->request->csrfToken."'},function(data){ $('#container').html(data); });
	}
	function playeraction(player, action, reason, time)
	{
		var reasontext;
		var profile = false;
		var reason = null;

		switch (action)
		{
			case 'ban':
				reasontext = 'Банни играча'
				break;
			case 'kick':
				reasontext = 'Кикни играча'
				break;
			case 'message':
				reasontext = 'Изпрати съобщение до играча'
				break;
			default:
				return false;
		}

		if(!confirm(reasontext + ' ' + player + '?')) {
			return false;
		}
		if(action == 'ban')
		{
			var reason = prompt('Въведи причина за бана', 'Чийтър');
			var bantime = prompt('Въведи време за бана в минути (1440 - ден, 10080 - седмица, 43200 - месец)', '1440');
			if(!reason || !bantime) {
				return false;
			}
		}

		if(action == 'message')
		{
			var reason = prompt('Въведете съобщение за играча ' + player, '');
			if(!reason)
			{
				return false;
			}
		}
		$('#loading').show();
		$.post(
			'".Yii::app()->createUrl('/serverinfo/context', array('id' => $server->id))."',
			{
				'ajax': 1,
				'action': action,
				'player': player,
				'reason': reason,
				'time': bantime
			},
			function(data){eval(data);}
		);
	}

	$(function(){
		$.contextMenu({
			selector: '.context-menu-one',
			callback: function(key, options) {
				var player = options.\$trigger.attr('id');
				playeraction(player, key);
			},
			items: {
				'ban': {name: 'Банни'},
				'separator': '-----',
				'kick': {name: 'Кикни'},
				'separator2': '-----',
				'message': {name: 'Изпращане на съобщение'},
			}
		});
	});
	", CClientScript::POS_END
	);

}
endif;
?>

<div id="container">
	<?php if($info): ?>
	<h2>Детайли за сървър &laquo;<?php echo $info['name']; ?>&raquo;</h2>
	<?php if(!Yii::app()->user->isGuest): ?>
	<p class="text-success">
		<i class="icon-exclamation-sign"></i>
		<i>Натиснете дясно копче върху играча за да отворите менюто</i>
	</p>
	<?php endif; ?>

	<div class="row-fluid">
		<div class="span7">
			<?php if(is_array($info['playersinfo']) && !empty($info['playersinfo'])): ?>
			<h5 class="text-center">Играчи</h5>
			<table class="table table-bordered" id="players">
				<thead>
					<th>
						Ник
					</th>
					<th style="text-align: center">
						Резултат
					</th>
					<th style="text-align: center">
						Време
					</th>
				</thead>
				<tbody>
					<?php
					foreach($info['playersinfo'] as $player):?>
						<tr class="context-menu-one" id="<?php echo CHtml::encode($player['name'])?>">
							<td><?php echo CHtml::encode($player['name'])?></td>
							<td style="text-align: center"><?php echo CHtml::encode($player['score'])?></td>
							<td style="text-align: center"><?php echo function_exists('query_live') ? $player['time'] : Prefs::date2word(intval($player['time']), FALSE, TRUE)?></td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<?php else: ?>
			<div class="alert alert-error">Няма играчи</div>
			<?php endif; ?>
		</div>
		<div class="span5">
			<h5 class="text-center">Информация</h5>
			<table class="table table-bordered">
				<tr>
					<td style="text-align: center" colspan="2">
						<?php echo $info['mapimg']; ?>
					</td>
				</tr>
				<tr>
					<td class="bold">
						IP:
					</td>
					<td>
						 <?php
						 echo CHtml::link(
								 CHtml::encode($server->address),
								 'steam://connect/' . CHtml::encode($server->address)
							 );
						 ?>
					</td>
				</tr>
				<tr>
					<td class="bold">
						Карта:
					</td>
					<td>
						 <?php echo CHtml::encode($info['map']); ?>
					</td>
				</tr>
				<tr>
					<td class="bold">
						Играчи:
					</td>
					<td>
						 <?php
						 echo CHtml::encode($info['players']) . '/' . CHtml::encode($info['playersmax']);
						 ?>
					</td>
				</tr>
				<?php if($info['nextmap']):?>
				<tr>
					<td class="bold">
						Следваща карта:
					</td>
					<td>
						 <?php echo CHtml::encode($info['nextmap']); ?>
					</td>
				</tr>
				<?php endif?>
				<?php if($info['timeleft']):?>
				<tr>
					<td class="bold">
						До смяна на картата:
					</td>
					<td>
						 <?php echo CHtml::encode($info['timeleft']); ?>
					</td>
				</tr>
				<?php endif?>
				<?php if($info['contact']):?>
				<tr>
					<td class="bold">
						Контакти:
					</td>
					<td>
						 <?php echo CHtml::encode($info['contact']); ?>
					</td>
				</tr>
				<?php endif?>
			</table>
		</div>
	</div>
	<?php else: ?>
	<h2>Детайли за сървър &laquo;<?php echo $server->hostname; ?>&raquo;</h2>
	<div class="alert alert-error">
		Сървърът не отговаря. Може би е спрян или се сменя картата.
	</div>
	<?php endif; ?>
</div>