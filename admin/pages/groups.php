<?
\Bitrix\Main\Loader::includeModule('gruppenattribute');
use Volex\GruppenAttribute as vGA;
use \Bitrix\Iblock;
?>
<?$currentLevel	= $navigation[$_GET['level']]; ?>
<?$entity = 'group';?>
<?$groups = new vGA\Groups;?>
<?$iblocks = new vGA\Iblocks;?>

<?$groupsList		= $groups->getAll();?>
<?$iblocksList		= $iblocks->getUnused();?>
<h1><?= $currentLevel['name']; ?></h1>
<div class="control">
	
	<select name="iblock_id" rel-type="table-column">
	<?foreach($iblocksList as $iblock) :?>
		<option value="<?= $iblock['ID']; ?>"><?= $iblock['NAME']; ?></option>
	<?endforeach;?>
	</select>

	<input rel-type="table-column" name="name" type="text" class="input_control" placeholder="название" value=""></input>
	<input rel-type="table-column" name="code" type="text" class="input_control" placeholder="код" value=""></input>
	<button class="button_add">+</button>
</div>
<div class="header">
	<h2>Список групп</h2>
</div>
<div class="list">
<table class="std_table">
	<tr>
		<th>
			Название
		</th>
		<th>
			Внешний код
		</th>
		<th>
			Связанный инфоблок
		</th>
		<th>
			Действия
		</th>
	</tr>
<?foreach($groupsList as $group) :?>
	<tr>
		<td>
			<a href="?level=<?= $currentLevel['child'];?>&id=<?= $group['ID']; ?>"><?= $group['NAME']; ?></a>
		</td>
		<td>
			<span> <?= $group['CODE']; ?> </span>
		</td>
		<td>
			<span> <?= $group['IBLOCK_NAME']; ?> </span>
		</td>
		<td>
			<button class="button_del" method="delete" rel-id="<?= $group['ID'];?>">х</button>
		</td>
	</tr>
<?endforeach;?>
</table>
</div>


<script>
	function addElement() {
		var element = collectData();
		console.log(element);
		sendQuery(element);
		getList();
	}
	$('button.button_add').on('click', function() {
		addElement();
	});

	function deleteElement(id) {
		sendQuery({
			method: 'delete',
			entity: 'groups',
			input_data: {ID: id}
		});
	}

	$('button.button_del').on('click', function() {
		deleteElement($(this).attr('rel-id'));
	});

	function collectData() {
		var element = {}
		element.method = 'add';
		element.entity = 'groups';
		element.input_data = {};
		$('[rel-type="table-column"]').each(function() {
			element.input_data[$(this).attr('name').toUpperCase()] = $(this).val();
		});
	
		return element;
	}
	
	function getList() {
			console.log( sendQuery({
				method: 'getAll',
				entity: 'groups'
			}));
	}

	function sendQuery(params) {
		$.get(
			'/local/modules/gruppenattribute/admin/gruppenattribute_ajax.php',
			params
		).done(function(response) {
			var data = JSON.stringify(response);
			console.log(data);
		});

	}

</script>
