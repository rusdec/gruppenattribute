<?
namespace Volex\GruppenAttribute;

use \Bitrix\Iblock;
use Volex\GruppenAttribute;

class IBlocks {
	
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

		$unfetchedResult = GruppenAttribute\IBlockTable::getList($params);

		return $unfetchedResult->FetchAll();
	}

	/**
	*	@return array
	*/
	public function getUnused($inputParams) {
		$params['select'] = (isset($inputParams['select'])) ? $inputParams['select'] : ['ID', 'NAME'];
		if (isset($inputParams['filter']))
			$params['filter'] = $inputParams['filter'];

		$unfetchedResult = Iblock\IblockTable::getList($params);

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

		$unfetchedUnused = Iblock\IblockTable::getList(
			[
				'select' => ['ID', 'NAME'],
				'filter' => ['!=ID' => $used_id]
			]
		);
		return $unfetchedUnused->FetchAll();
	}

}

class Groups {
	
	/**
	*	@return array
	*/
	private function get($inputParams = []) {
		$params['select'] = (isset($inputParams['select'])) ? $inputParams['select'] : ['*'];
		if (isset($inputParams['filter']))
			$params['filter'] = $inputParams['filter'];

		$unfetchedResult = GruppenAttribute\GroupTable::getList($params);

		return $unfetchedResult->FetchAll();
	}

	/**
	*	@return array
	*/
	public function getAll() {
		return $this->get();
	}
	
	/**
	*	@param int
	*	@return array
	*/
	public function getById($id) {
		return $this->get(['filter' => ['ID' => $id]]);
	}

	/**
	*	@param int
	*	@return array
	*/
	public function getAllByIblockId($id) {
		return $this->get(['filter' => ['IBLOCK_ID' => $id]]);
	}

	/**
	*	@param string
	*	@return array
	*/
	public function getByCode($code) {
		return $this->get(['filter' => ['CODE' => $code]]);
	}

	/**
	* 
	*	Функции изменения
	*
	*/
	
	/**
	*	@param [NAME => string, CODE => string, IBLOCK_ID => int]
	*	@return int|bool
	*/
	public function addGroup($params) {
		return GruppenAttribute\GroupTable::add($params);
	}

	/**
	*	@param int
	*	@return bool
	*/
	public function deleteGroup($id) {
		return GruppenAttribute\GroupTable::delete($id);
	}

	/**
	*	@param [NAME => string, CODE => string, IBLOCK_ID => int]
	*	@return bool
	*/
	public function updateGroup($params) {
		$id = $params['ID'];
		unset($params['ID']);
		return GruppenAttribute\GroupTable::update($id, $params);
	}
}
