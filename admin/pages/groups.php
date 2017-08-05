<?
\Bitrix\Main\Loader::includeModule('gruppenattribute');
use Volex\GruppenAttribute as vGA;
?>
<?$currentLevel	= $navigation[$_GET['level']]; ?>
<?$groups = new vGA\Groups;?>
<?$iblocks = new vGA\IBlocks;?>

<?$groupsList		= $groups->getAll();?>
<?$iblocksList		= $iblocks->getAll();?>
<h1><?= $currentLevel['name']; ?></h1>
<div class="control">
	
	<select name="iblock_id">
	<?foreach($iblocksList as $iblock) :?>
		<option value="<?= $iblock['ID']; ?>"><?= $iblock['NAME']; ?></option>
	<?endforeach;?>
	</select>

	<input name="group_name" type="text" class="input_control"></input>
	<button class="button_add">+</button>
</div>
<div class="header">
	<h2>Список групп</h2>
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
