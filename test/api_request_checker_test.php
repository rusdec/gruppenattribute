<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?
use Volex\GruppenAttribute as VGA;
use \Bitrix\Iblock as IB;

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('gruppenattribute');

$requests_must_exists = [
	'groups' => [
		'entity' => 'groups',
		'method' => 'getAll'
	],
	'iblocks' => [
		'entity' => 'iblocks',
		'method' => 'getUsed'
	],
	'sections' => [
		'entity' => 'sections',
		'method' => 'update'
	]
];

foreach($requests_must_exists as $request) {
	test_field_entity_method_must_exists($request);
}


$requests_must_not_exists = [
	'groups' => [
		'entity' => 'groupfdfds',
		'method' => 'getAdfdll'
	],
	'iblocks' => [
		'entity' => 'ibdflocks',
		'method' => 'getUdfsed'
	],
	'sections' => [
		'entity' => 'secdftions',
		'method' => 'ufdpdate'
	]
];

foreach($requests_must_not_exists as $request) {
	test_field_entity_method_must_not_exists($request);
}




function test_field_entity_method_must_exists($request) {
	$test_name = 'Проверка переданных параметров Fields, Entity, Method: '.'entity = '.$request['entity'];
	$test_rule = 'должно существовать';
	$api_request_checker = new VGA\ApiRequestCheck($request);
	$result = $api_request_checker->checkRequest();
	echo "<h3>".$test_name."</h3>";
	echo "<h4>".$test_rule."</h4>";
	if ($result['has_error']) {
		echo "<p style='color: red'>провал</p>";
		echo "<pre>";
		var_dump($result);
		echo "</pre>";
	} else {
		echo "<p style='color: green'>пройден</p>";
	}
}
function test_field_entity_method_must_not_exists($request) {
	$test_name = 'Проверка переданных параметров Fields, Entity, Method: '.'entity = '.$request['entity'];
	$test_rule = 'не должно существовать';
	$api_request_checker = new VGA\ApiRequestCheck($request);
	$result = $api_request_checker->checkRequest();
	echo "<h3>".$test_name."</h3>";
	echo "<h4>".$test_rule."</h4>";
	if (!$result['has_error']) {
		var_dump($result);
	} else {
		echo "<p style='color: green'>пройден</p>";
	}
}
