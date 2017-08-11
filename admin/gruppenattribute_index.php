<?define('ADMIN_MODULE_NAME', 'gruppenattribute');?>
<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';?>


<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';?>

<link rel="stylesheet" href="/local/modules/gruppenattribute/static/css/style.css">
<link rel="stylesheet" href="/local/modules/gruppenattribute/static/css/font-awesome.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">

<script src="/local/modules/gruppenattribute/static/jquery-3.2.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>

<?$navigation = [
	'iblocks' => [
		'name'	=> 'Инфоблоки',
		'code'	=> 'iblocks',
		'parent' => NULL,
		'child'	=> 'groups'
	],
	'groups' => [
		'name'	=> 'Группы',
		'code'	=> 'groups',
		'parent'	=> NULL,
		'child'	=> 'group'
	],
	'group' => [
		'name'	=> 'Группа',
		'code'	=> 'group',
		'parent' => 'groups',
		'child'	=> 'section'
	],
	'section' => [
		'name' => 'Раздел',
		'code' => 'section',
		'parent' => 'group',
		'child' => NULL
	],
	'properties' => [
		'name'	=> 'Свойства',
		'code'	=> 'properties',
		'parent'	=> 'groups',
		'child'	=> NULL
	]
]?>

<?if ($navigation[$_GET['level']]['parent'] !== NULL) :?>
	<?$parent_href = (isset($_GET['parent_id'])) ? '&id='.$_GET['parent_id'] : ''?>
	<a href="?level=<?= $navigation[$_GET['level']]['parent']; ?><?= $parent_href; ?>">Назад</a>
<?endif;?>

<?require('pages/'.$_GET['level'].'.php');?>

<br>
<br class="clear">
<script>
$(document).ready(function() {
	$('#datatable').dataTable({
		"dom": '<"bottom"iflp<"clear">>rt',
		"aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
		"iDisplayLength": 50,
	});
});
</script>
<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';?>
