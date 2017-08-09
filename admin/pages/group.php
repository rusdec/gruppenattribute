<?
\Bitrix\Main\Loader::includeModule('gruppenattribute');
use Volex\GruppenAttribute as VGA;
?>
<?$currentLevel = $navigation[$_GET['level']]; ?>

<?$groupEntity = new VGA\Groups;?>
<?$group = $groupEntity->getById($_GET['id']);?>

<?$sectionEntity = new VGA\Sections;?>
<?$sectionUsedList = $sectionEntity->getLinkedToGroupId($_GET['id']);?>
<?$sectionUnusedList = $sectionEntity->getUnlinkedToGroupId($_GET['id']);?>

<h1><?= $currentLevel['name']; ?> | <?= $group['NAME']; ?></h1>
<div class="control">
	
	<select name="section_id" rel-type="table-column">
	<?foreach($sectionUnusedList as $section) :?>
		<option value="<?= $section['ID']; ?>"><?= $section['NAME']; ?></option>
	<?endforeach;?>
	</select>
	<input rel-type="table-column" name="group_id" type="hidden" class="input_control" value="<?= $group['ID']; ?>"></input>
	<button class="button_add fa fa-floppy-o"></button>
</div>

<div class="header">
	<h2>Список разделов</h2>
</div>
<div class="list">
<table class="std_table">
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
			<a href="?level=<?= $currentLevel['child'];?>&id=<?= $section['ID']; ?>&parent_id=<?= $group['ID'];?>&group_id=<?= $group['ID']; ?>"><?= $section['NAME']; ?></a>
		</td>
		<td>
			<button class="button_del fa fa-remove" rel-id="<?= $section['ID'];?>"></button>
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
		group_id = $('input[name="group_id"]').val();
		sendQuery({
			method: 'delete',
			entity: 'sections',
			input_data: {
				section_id: id,
				group_id: group_id	
			}
		});
	}

	$('button.button_del').on('click', function() {
		deleteElement($(this).attr('rel-id'));
	});

	function collectData() {
		var element = {}
		element.method = 'add';
		element.entity = 'sections';
		element.input_data = {};
		$('[rel-type="table-column"]').each(function() {
			element.input_data[$(this).attr('name')] = $(this).val();
		});
	
		return element;
	}
	
	function getList() {
			console.log( sendQuery({
				method: 'getUsed',
				entity: 'sections'
			}));
	}

	function sendQuery(params) {
		if (params.hasOwnProperty('input_data'))
			params.input_data = capitalize(params.input_data);
		$.get(
			'/local/modules/gruppenattribute/admin/gruppenattribute_ajax.php',
			params
		).done(function(response) {
			var data = JSON.stringify(response);
			console.log(data);
		});
	}

	function capitalize(params) {
		capitalParams = {};
		Object.keys(params).forEach(function(key) {
			capitalParams[key.toUpperCase()] = params[key];
		});

		return capitalParams;
	}

</script>
