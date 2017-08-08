<?
namespace Volex\GruppenAttribute;

class Base {

	protected $result = ['has_error' => true];

	public function hasError() {
		return $this->result['has_error'];
	}
	
	public function getError() {
		return $this->result;
	}

	protected function setError($params) {
		$this->result['has_error'] = true;
		$this->result['messages'][] = [
				'text' => $params['text'],
				'detail' => $params['detail']
		];
	}

	protected function setErrorFalse() {
		$this->result['has_error'] = false;
	}


}
