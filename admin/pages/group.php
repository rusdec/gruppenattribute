<?
\Bitrix\Main\Loader::includeModule('gruppenattribute');
use Volex\GruppenAttribute as VGA;
?>
<?$currentLevel = $navigation[$_GET['level']]; ?>

<?$groupEntity = new VGA\Groups;?>
<?$group = $groupEntity->getById($_GET['id']);?>

<?$sectionEntity = new VGA\Properties;?>
<?$sectionUsedList = $sectionEntity->getUsed();?>
<?$sectionUnusedList = $sectionEntity->getUnused();?>

<h1><?= $currentLevel['name']; ?> | <?= $group['NAME']; ?></h1>
<div class="control">
	
	<select name="iblock_id" rel-type="table-column">
	<?foreach($sectionUnusedsList as $section) :?>
		<option value="<?= $section['ID']; ?>"><?= $section['NAME']; ?></option>
	<?endforeach;?>
	</select>
	<input rel-type="table-column" name="name" type="text" class="input_control" placeholder="название" value=""></input>
	<button class="button_add">+</button>
</div>

<div class="list">
<table>
	<tr>
		<th>
			Название
		</th>
		<th>
			Действия
		</th>
	</tr>
<?foreach($sectionUsedList as $section) :?>
	<tr>
		<td>
			<?= $section['NAME']; ?>	
		</td>
		<td>
			<button class="button_del" rel-id="<?= $section['ID'];?>">х</button>
		</td>
	</tr>
<?endforeach;?>
</table>
</div>
