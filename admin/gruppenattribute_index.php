<?define('ADMIN_MODULE_NAME', 'gruppenattribute');?>
<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';?>


<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';?>

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
		'child'	=> ''
	],
	'properties' => [
		'name'	=> 'Свойства',
		'code'	=> 'properties',
		'parent'	=> 'groups',
		'child'	=> NULL
	]
]?>

<?if ($navigation[$_GET['level']]['parent'] !== NULL) :?>
	<a href="?level=<?= $navigation[$_GET['level']]['parent']; ?>">Назад</a>
<?endif;?>

<?require('pages/'.$_GET['level'].'.php');?>

<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';?>
