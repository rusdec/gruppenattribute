<?

namespace Volex\GruppenAttribute;

use \Bitrix\Main\Localization\Loc;
use Volex\GruppenAttribute;

Loc::loadMessages(__FILE__);

final class ApiRequestCheck extends ApiStructure {

	protected $src_request;
	protected $request;

	/**
	*	@var array $result {
	*		@option boolean	"has_error"
	*		@option array		"error_messages" {
	*			@option string "text"
	*			@option string "detail"
	*		}
	*	}
	*/
	protected $result = [
		'has_error'			=> false,
		'error_messages'	=> []
	];

	function __construct($request) {
		$this->src_request = $request;
		$this->buildArrayFromSrcRequest();
		$this->checkFields();

		foreach($this->fields as $field => $properties) {

			if(isset($request[$fieldName])) {
				if($properties['type'] == 'string' && !in_array()) {
					$this->setError([
						'text'	=> Loc::getMessage('NOT_EXISTS_METHOD'),
						'detail' => $request[$fieldName]
					]);
				}
				if($properties['type'] == 'json')
					$request[$fieldName] = json_decode($requset[$fieldName]);

				$this->request[$fieldName] = $request[$fieldName];
			}
		}
	}

	protected function buildArrayFromSrcRequest() {
		foreach($this->fields as $field) {
			if(isset($this->src_request[$field]))
				$this->request[$field] = $src_request[$field];
		}
	}

	protected function checkFields() {
		foreach($this->fields as $field => $properties) {
			if($properties['require'] && !isset($this->request[$field])) {
				$this->setError([
					'text'	=> Loc::getMessage('NOT_EXISTS_REQUIRE_FIELD'),
					'detail'	=> $field
				]);
				continue();
			}
		}
	}

	/**
	*	@param string $field
	*/
	protected function checkField($field) {
		if(!isset($this->fields[$field]))	
			$this->setError([
				'text'	=> Loc::getMessage('NOT_EXISTS_ENTITY'),
				'detail'	=> $entity
			]);
		}
	}

	/**
	*	@param string $entity
	*/
	protected function checkEntity($entity) {
	
		if(!isEntityExists($entity)) {
			$this->setError([
				'text'	=> Loc::getMessage('NOT_EXISTS_ENTITY'),
				'detail'	=> $entity
			]);
		}
	}

	/**
	*	@param array $params {
	*		@option string "entity"
	*		@option string "method"
	*	}
	*/
	protected function checkMethod($params) {
		if(!isMethodExists($this->entities[$params['entity']][$params['method']]))
			$this->setError([
				'text'	=> Loc::getMessage('NOT_EXISTS_METHOD'),
				'detail'	=> $method
			]);
		}
	}

	#TODO: + возм.добавлять текст ошибок
	protected function setError($params) {
		$this->result['has_error'] = true;
		$this->result['error_messages'][] = [
			'text' => $params['text'],
			'detail' => (isset($params['detail'])) ? $params['detail'] : ''
		];
	}

	public function hasError() {
		return $this->result['has_error'];
	}

	public function getErrors() {
		return $this->result['error_messages'];
	}

	public function getRequest() {
		return $this->request;
	}
		
}
