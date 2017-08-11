<?
\Bitrix\Main\Loader::includeModule('gruppenattribute');
use Volex\GruppenAttribute as VG;
?>
<?$iblocksList = VG\Iblocks::getAll();?>
<?$href = [];?>
<h1><?= $currentLevel['name']; ?></h1>
<table>
	<tbody>
<?foreach($iblocksList as $iblock) :?>
	<?$href['level'] = 'level='.$navigation[$_GET['level']]['child'];?>
	<?$href['iblock_id'] = 'iblock_id='.$iblock['ID']?>
	<tr>
		<td>
			<a href="?<?= implode('&', $href) ?>"><?= $iblock['NAME']; ?></a>
		</td>
	</tr>
	</tbody>
<?endforeach;?>
</table>
