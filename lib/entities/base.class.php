<?
namespace Volex\GruppenAttribute;

class Base {

	protected $result = ['has_error' => true];

	/**
	*	@return boolean
	*/
	public function hasError() {
		return $this->result['has_error'];
	}

	/**
	*	@return array $result
	*/
	public function getError() {
		return $this->result;
	}

	protected function setError($params) {
		$this->result['has_error'] = true;
		$this->result['messages'][] = [
				'text'	=> $params['text'],
				'detail'	=> $params['detail']
		];
	}

	protected function setErrorFalse() {
		$this->result['has_error'] = false;
	}

	/**
	*	@return array $structure
	*/
	protected function getStructure() {
		return $this->structure;
	}
}
