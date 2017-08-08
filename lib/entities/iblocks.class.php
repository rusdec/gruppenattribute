<?
namespace Volex\GruppenAttribute;

\Bitrix\Main\Loader::includeModule('iblock');

use \Bitrix\Iblock;
use Volex\GruppenAttribute;

class Iblocks extends Base {

	/**
	*	@return array
	*/
	public function getAll() {
		return $this->getUnused();
	}

	/**
	*	@return array
	*/
	public function getAllUsed() {
		return $this->getUsed();
	}

	/**
	*	@return array
	*/
	public function getUsedById($id) {
		return $this->getUsed(['filter'=>['ID'=>$id]]);
	}

	/**
	*	@return array
	*/
	public function getUnusedById($id) {
		return $this->getUnused(['filter'=>['ID'=>$id]]);
	}

	/**
	*	@return array
	*/
	public function getUsed($inputParams) {
		$params['select'] = (isset($inputParams['select'])) ? $inputParams['select'] : ['*'];
		if (isset($inputParams['filter']))
			$params['filter'] = $inputParams['filter'];

		$unfetchedResult = GruppenAttribute\IblockTable::getList($params);

		return $unfetchedResult->FetchAll();
	}

	/**
	*	@return array
	*/
	public function getUnused($inputParams) {
		$params['select'] = (isset($inputParams['select'])) ? $inputParams['select'] : ['ID', 'NAME'];
		if (isset($inputParams['filter']))
			$params['filter'] = $inputParams['filter'];
		
		$unfetchedResult = \Bitrix\Iblock\IblockTable::getList($params);

		return $unfetchedResult->FetchAll();
	}

	/**
	*	@return array
	*/
	public function getAllUnused() {
		$usedTmp = $this->getAllUsed();
		$used_id = [];
		foreach($usedTmp as $tmp) {
			$used_id[] = $tmp['ID'];
		}

		$unfetchedUnused = \Bitrix\Iblock\IblockTable::getList(
			[
				'select' => ['ID', 'NAME'],
				'filter' => ['!=ID' => $used_id]
			]
		);
		return $unfetchedUnused->FetchAll();
	}

	public function add($params) {
		$result = GruppenAttribute\IblockTable::add($params);

		if ($result->isSuccess()) {
			$this->result['has_error'] = false;
			return $result->getId();

		} else {
			$this->setError(['text' => $result->getErrorMessages(), 'detail' => $params]);
			return $this->result;
		}
	}

}
