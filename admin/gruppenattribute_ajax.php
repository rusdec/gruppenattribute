<?

define('ADMIN_MODULE_NAME', 'gruppenattribute');
use Volex\GruppenAttribute;

function response($data) {
	header('Content-Type: application/json;charset=utf-8');
	echo json_encode($data);
}


