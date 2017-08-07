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
	}

	public function checkRequest() {
		$this->checkFields();
		if($this->hasError())
			return $this->result;

		$this->checkEntity($this->request['entity']);
		if($this->hasError())
			return $this->result;

		$this->checkMethod(['entity' => $this->request['entity'], 'method' => $this->request['method']]);
		if($this->hasError())
			return $this->result;
	}


	protected function buildArrayFromSrcRequest() {
		foreach($this->getFields() as $field => $properties) {
			if(array_key_exists($field, $this->src_request))
				$this->request[$field] = $this->src_request[$field];
		}
	}

	protected function checkFields() {
		foreach($this->getFields() as $field => $properties) {
			if($properties['require'] && !array_key_exists($field, $this->request)) {
				$this->setError([
					'text'	=> Loc::getMessage('NOT_EXISTS_REQUIRE_FIELD'),
					'detail'	=> $field
				]);
				continue;
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

	/**
	*	@param string $entity
	*/
	protected function checkEntity($entity) {
		if(!$this->isEntityExists($entity)) {
			$this->setError([
				'text'	=> Loc::getMessage('NOT_EXISTS_ENTITY'),
				'detail'	=> $entity
			]);
		}
	}

	/**
	*  @param array $params {
	*     @option string "entity"
	*     @option string "method"
	*  }
	*/
	protected function checkMethod($params) {
		if(!$this->isMethodExists($params))
			$this->setError([
				'text'	=> Loc::getMessage('NOT_EXISTS_METHOD'),
				'detail'	=> $method
			]);
	}

	/**
	*  @param array $params {
	*     @option string "text"
	*     @option string "detail"
	*  }
	*/
	protected function setError($params) {
		$this->result['has_error'] = true;
		$this->result['error_messages'][] = [
			'text' => $params['text'],
			'detail' => (isset($params['detail'])) ? $params['detail'] : ''
		];
	}

	/**
	*	@return bool
	*/
	public function hasError() {
		return $this->result['has_error'];
	}

	/**
	*	@return array $this->result["error_mesages"] 
	*/
	public function getErrors() {
		return $this->result['error_messages'];
	}

	/**
	*	@return array $this->request
	*/
	public function getRequest() {
		return $this->request;
	}
		
}
