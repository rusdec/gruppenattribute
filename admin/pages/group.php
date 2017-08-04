<?
\Bitrix\Main\Loader::includeModule('gruppenattribute');
use Volex\GruppenAttribute as VG;
?>
<?$groupsList = VG\Groups::getAll();?>
<?$currentLevel = $navigation[$_GET['level']]; ?>
<h1><?= $currentLevel['name']; ?></h1>
<div class="control">
	<input name="group_name" class="input_control"></input>
	<button class="button_add">+</button>
</div>
<div class="header">
	<h2></h2>
</div>
<div class="list">
<table>
<?foreach($groupsList as $group) :?>
	<tr>
		<td>
			<a href="?level=<?= $currentLevel['child'];?>"><?= $group['NAME']; ?></a>
		</td>
		<td>
			<button class="button_del" rel-id="<?= $group['ID'];?>">х</button>
		</td>
	</tr>
<?endforeach;?>
</table>
</div>
