<?
\Bitrix\Main\Loader::includeModule('gruppenattribute');
use Volex\GruppenAttribute as VGA;
?>
<?$currentLevel = $navigation[$_GET['level']]; ?>

<?$sectionEntity = new VGA\Sections;?>
<?$section = $sectionEntity->getUsedById($_GET['id']);?>

<?$propertyEntity = new VGA\Properties;?>
<?$propertyUsedList = $propertyEntity->getLinkedToSectionId($_GET['id']);?>
<?$propertyUnusedList = $propertyEntity->getUnlinkedToSectionId($_GET['id']);?>

<h1><?= $currentLevel['name']; ?> | <?= $section['NAME']; ?></h1>
<div class="control">
	
	<select name="property_id" rel-type="table-column">
	<?foreach($propertyUnusedList as $property) :?>
		<option value="<?= $property['ID']; ?>"><?= $property['NAME']; ?></option>
	<?endforeach;?>
	</select>
	<input rel-type="table-column" name="sort1" type="text" placeholder="сортировка">
	<input rel-type="table-column" name="section_id" type="hidden" class="input_control" value="<?= $section['ID']; ?>"></input>
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
		</td>
		<td>
			<button class="button_upd fa fa-floppy-o edited" rel-id="<?= $property['ID'];?>" title="Обновить"></button>
			<button class="button_del fa fa-remove" rel-id="<?= $property['ID'];?>" title="Удалить"></button>
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
		sendQuery(element);
		getElements();
	}

	function deleteElement(id) {
		sendQuery({
			method: 'delete',
			entity: 'properties',
			input_data: {
				property_id: id,
				section_id: getSectionId()	
			}
		});
	}

	function updateElement(id) {
		var element = collectData('[rel-type="table-column-update"][rel-id="'+id+'"]');
		element.method = 'update';
		element.entity = 'properies';
		element.input_data.section_id = getSectionId();
		element.input_data.property_id = id;
		console.log(element);
	//	sendQuery(element);
		getElements();
	}

	$('button.button_add').on('click', function() {
		addElement();
		location.reload(); <?#todo?>
	});
	$('button.button_upd').on('click', function() {
		updateElement($(this).attr('rel-id'));
		location.reload(); <?#todo?>
	});
	$('button.button_del').on('click', function() {
		deleteElement($(this).attr('rel-id'));
		location.reload(); <?#todo?>
	});

	function getSectionId() {
		return $('input[name="section_id"]').val();
	}

	function collectData(selector) {
		var element = {}
		element.input_data = {};
		$(selector).each(function() {
			element.input_data[$(this).attr('name')] = $(this).val();
			console.log($(this).attr('name'), $(this).val());
		});
	
		return element;
	}
	<?#todo: строить интерфейс до json-данным из гет-запроса?>
	function getElements() {
			console.log( sendQuery({
				method: 'getUsed',
				entity: 'properties'
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
