<?
\Bitrix\Main\Loader::includeModule('gruppenattribute');
use Volex\GruppenAttribute as VGA;
?>
<?$currentLevel = $navigation[$_GET['level']]; ?>

<?$sectionEntity = new VGA\Sections;?>
<?$groupEntity = new VGA\Groups;?>
<?$section = $sectionEntity->getUsedById($_GET['id']);?>
<?$group = $groupEntity->getById($_GET['group_id']);?>

<?$propertyEntity = new VGA\Properties;?>
<?$propertyUsedList = $propertyEntity->getLinkedToSectionId(['SECTION_ID' => $_GET['id'], 'GROUP_ID' => $_GET['group_id']]);?>

<?#$propertyUsedList = $propertyEntity->getUsed();?>

<?$propertyUnusedList = $propertyEntity->getUnlinkedToSectionId(['SECTION_ID' => $_GET['id'], 'GROUP_ID' => $_GET['group_id']]);?>

<h1><?= $currentLevel['name']; ?> | <?= $section['NAME']; ?></h1>
<div class="control">
	
	<select name="property_id" rel-type="table-column">
	<?foreach($propertyUnusedList as $property) :?>
		<option value="<?= $property['ID']; ?>"><?= $property['NAME']; ?></option>
	<?endforeach;?>
	</select>
	<input rel-type="table-column" name="sort" type="text" placeholder="сортировка">
	<input rel-type="table-column-position" name="section_id" type="hidden" class="input_control" value="<?= $section['ID']; ?>"></input>
	<input rel-type="table-column-position" name="group_id" type="hidden" class="input_control" value="<?= $group['ID']; ?>"></input>
	<button class="button_add fa fa-floppy-o" title="Добавить"></button>
</div>

<div class="header">
	<h2>Прикреплённые свойства</h2>
</div>
<div class="list">
<table class="std_table">
	<tr>
		<th>
			Название
		</th>
		<th>
			Сорт.
		</th>
		<th>
			Действия
		</th>
	</tr>
<?foreach($propertyUsedList as $property) :?>
	<tr>
		<td>
			<span><?= $property['NAME']; ?></span>
		</td>
		<td>
			<input class="input_sort" size=5 rel-type="table-column-update" name="sort" type="text" value="<?= $property['SORT']; ?>" rel-id="<?= $property['ID'];?>">
			<input type="hidden" rel-type="table-column-update" name="link_id" value="<?= $property['LINK_ID']; ?>" rel-id="<?= $property['ID'];?>">
		</td>
		<td>
			<button class="button_upd fa fa-floppy-o edited" rel-id="<?= $property['ID'];?>" title="Обновить"></button>
			<button class="button_del fa fa-remove" rel-id="<?= $property['LINK_ID'];?>" title="Удалить"></button>
		</td>
	</tr>
<?endforeach;?>
</table>
</div>

<script>
	function addElement() {
		var element = collectData('[rel-type="table-column"]');
		element.method = 'add';
		element.entity = 'properties';
		element.input_data.group_id = getGroupId();
		element.input_data.section_id = getSectionId();
		sendQuery(element);
	//	getElements();
	}

	function deleteElement(id) {
		sendQuery({
			method: 'delete',
			entity: 'properties',
			input_data: {
				id: id,
			}
		});
	}

	function updateElement(id) {
		var element = collectData('[rel-type="table-column-update"][rel-id="'+id+'"]');

		element.method = 'update';
		element.entity = 'properties';
		sendQuery(element);
	}

	$('button.button_add').on('click', function() {
		addElement();
		//location.reload();
	});
	$('button.button_upd').on('click', function() {
		updateElement($(this).attr('rel-id'));
		//location.reload(); 
	});
	$('button.button_del').on('click', function() {
		deleteElement($(this).attr('rel-id'));
		//location.reload(); 
	});

	function getSectionId() {
		return $('input[name="section_id"]').val();
	}
	function getGroupId() {
		return $('input[name="group_id"]').val();
	}

	function collectData(selector) {
		var element = {}
		element.input_data = {};
		$(selector).each(function() {
			element.input_data[$(this).attr('name')] = $(this).val();
		});
	
		return element;
	}
	<?#todo: строить интерфейс до json-данным из гет-запроса?>
	function getElements() {
			sendQuery({
				method: 'getUsed',
				entity: 'properties',
				input_data: getGroupId()
			});
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
