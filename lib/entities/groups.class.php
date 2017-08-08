<?
namespace Volex\GruppenAttribute;

use \Bitrix\Iblock;
use Volex\GruppenAttribute;

class Groups extends Base {
	
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
	
	public function callMethod($params) {
		switch ($params['method']) {
			case 'getAll':
				return $this->getAll();
			break;
			case 'add':
				$iblockId = $params['input_data']['IBLOCK_ID'];
				$iblockEntity = new GruppenAttribute\Iblocks;

				$iblock = $iblockEntity->getUsedById($iblockId);
				if (count($iblock) > 0) {
					return $this->add($params['input_data']);
				} else {
					$iblock = $iblockEntity->getUnusedById($iblockId);
					if (count($iblock) > 0) {
						$res = $iblockEntity->add(['ID' => $iblock[0]['ID'], 'NAME' => $iblock[0]['NAME']]);
						if ($res['has_error'])
							return $res;

						return $this->add($params['input_data']);
					} else {
						#ошибка. Нет такого инфоблока
					}
				}
			break;
			case 'delete':
				return $this->delete($params['input_data']);
			break;
		}
	}

	/**
	*	@return array
	*/
	public function getAll() {
		return $this->get([
			'select' => ['ID','NAME', 'CODE', 'IBLOCK_NAME' => 'IBLOCK.NAME']
		]);
	}
	
	/**
	*	@param int $id
	*	@return array
	*/
	public function getById($id) {
		$src = $this->get(['filter' => ['ID' => $id]]);
		if (count($src) > 0) {
			$result = [];
			foreach($src[0] as $key => $value) {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	/**
	*	@param int $id
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
	public function add($params) {
		return GruppenAttribute\GroupTable::add($params);
	}

	/**
	*	@param int
	*	@return bool
	*/
	public function delete($id) {
		return GruppenAttribute\GroupTable::delete($id);
	}

	/**
	*	@param [NAME => string, CODE => string, IBLOCK_ID => int]
	*	@return bool
	*/
	public function update($params) {
		$id = $params['ID'];
		unset($params['ID']);
		return GruppenAttribute\GroupTable::update($id, $params);
	}
}
