<?
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

\Bitrix\Main\Loader::includeModule('gruppenattribute');

use Volex\GruppenAttribute as VGA;
use \Bitrix\Iblock;


$shared_data = [
	'iblocks' => [
		'ID' => 99,
		'NAME' => 'Тестовый инфоблок'
	],
	'groups' => [
			'ID' => 20,
			'NAME' => 'Тестовая группа1',
			'CODE' => 'Teestovaya gruppa1'
	],
];
function printOk() {
	echo "<p style='color: green'>Положительно</p>";
}

function printBad() {
	echo '<p style="color: red">Провал</p>';
}

function printResult($result, $data) {
	if ($result) {
		printOk();
	} else {
		printBad();
		echo "<pre>";
		var_dump($result);
		echo "</pre>";
	}
}

function error_must_be_false($result) {
	echo "<span>Ошибка должна быть false</span>";
	printResult(!$result['has_error'], $result);
}

function id_must_be($result) {
	echo "Должен вернуться ID";
	printResult(array_key_exists('ID', $result), $result);
}

/**
*	@param array $params {
*		@option string "entity"
*		@option array "params" {
*			@option string "method"
*			@option array "input_data"
*		}
*	}
*	@return array
*/
function execute_method($params) {
	$class = VGA\Factory::instance($params['entity']);
	return $class->callMethod($params['params']);
}


/**
*	@param array $params {
*		@option string "entity"
*		@option array "params" {
*			@option string "method"
*			@option array "input_data"
*		}
*	}
*	@return array
*/
function entity_iblock_test($shared_data) {

	$test_name = 'entity_iblock_test';
	$test_data = [
	[
		'entity'	=> 'iblocks',
		'params'	=> [
			'method' => 'add',
			'input_data' => [
				'NAME' => $shared_data['iblocks']['NAME'],
				'ID' => $shared_data['iblocks']['ID']
			]
		]
	],
	[
		'entity'	=> 'iblocks',
		'params'	=> [
			'method' => 'delete',
			'input_data' => [
				'ID' => $shared_data['iblocks']['ID']
			]
		]
	]
	];

	echo "\n$test_name";
	foreach($test_data as $params) {
		switch ($params['params']['method']) {

			case 'add':
				echo "<br>Method: add";
				$result = execute_method($params);
				id_must_be($result);
				#todo: must return...
			break;

			case 'delete':
				echo "<br>Method: delete";
				$result = execute_method($params);
				error_must_be_false($result);
				#todo: must return...
			break;

			default:
				echo "<br>неизвестный метод: ".$params['params']['method'];
			break;
		}
	}
}

function entity_group_test($shared_data) {
	$test_name = 'entity_group_test';

	$test_data = [
		[
			'entity'	=> 'groups',
			'params'	=> [
				'method' => 'add',
				'input_data' => [
					'NAME' => $shared_data['groups']['NAME'],
					'ID' => $shared_data['groups']['ID'],
					'CODE' => $shared_data['groups']['CODE'],
					'IBLOCK_ID' => $shared_data['iblocks']['ID']
				]
			]
		],
		[
			'entity'	=> 'groups',
			'params'	=> [
				'method' => 'delete',
				'input_data' => [
					'ID' => $shared_data['groups']['ID'],
				]
			]
		],
	];
	echo "\n$test_name";
	foreach($test_data as $params) {
		switch ($params['params']['method']) {

			case 'add':
				echo "<br>Method: add";
				$result = execute_method($params);
				id_must_be($result);
				#todo: must return...
			break;

			case 'delete':
				echo "<br>Method: delete";
				$result = execute_method($params);
				error_must_be_false($result);
				#todo: must return...
			break;

			default:
				echo "<br>неизвестный метод: ".$params['params']['method'];
			break;
		}
	}

}

entity_iblock_test($shared_data);
entity_group_test($shared_data);

