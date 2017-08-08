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
	<input rel-type="table-column" name="section_id" type="hidden" class="input_control" value="<?= $section['ID']; ?>"></input>
	<button class="button_add">+</button>
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
			Действия
		</th>
	</tr>
<?foreach($propertyUsedList as $property) :?>
	<tr>
		<td>
			<span><?= $property['NAME']; ?></span>
		</td>
		<td>
			<button class="button_del" rel-id="<?= $property['ID'];?>">х</button>
		</td>
	</tr>
<?endforeach;?>
</table>
</div>

<script>
	function addElement() {
		var element = collectData();
		sendQuery(element);
		getList();
	}
	$('button.button_add').on('click', function() {
		addElement();
	});

	function deleteElement(id) {
		section_id = $('input[name="section_id"]').val();
		sendQuery({
			method: 'delete',
			entity: 'properties',
			input_data: {
				property_id: id,
				section_id: section_id	
			}
		});
	}

	$('button.button_del').on('click', function() {
		deleteElement($(this).attr('rel-id'));
	});

	function collectData() {
		var element = {}
		element.method = 'add';
		element.entity = 'properties';
		element.input_data = {};
		$('[rel-type="table-column"]').each(function() {
			element.input_data[$(this).attr('name')] = $(this).val();
		});
	
		return element;
	}
	
	function getList() {
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
