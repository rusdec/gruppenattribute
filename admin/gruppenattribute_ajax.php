<?
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

\Bitrix\Main\Loader::includeModule('gruppenattribute');

use Volex\GruppenAttribute as VGA;
use \Bitrix\Iblock;

function response($data) {
	header('Content-Type: application/json;charset=utf-8');
	echo json_encode($data);
	die();
}

$request_checker = new VGA\ApiRequestCheck($_GET);
$check_result = $request_checker->checkRequest();
if ($check_result['has_error'])
	response($check_result);

$class = VGA\Factory::instance($_GET['entity']);
response($class->callMethod($_GET));
