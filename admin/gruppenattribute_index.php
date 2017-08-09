<?define('ADMIN_MODULE_NAME', 'gruppenattribute');?>
<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';?>


<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';?>

<script src="/local/modules/gruppenattribute/static/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" href="/local/modules/gruppenattribute/static/css/style.css">
<link rel="stylesheet" href="/local/modules/gruppenattribute/static/css/font-awesome.min.css">
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

<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';?>
