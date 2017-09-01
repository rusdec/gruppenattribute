<?
namespace Volex\GruppenAttribute;

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('main');

use Volex\GruppenAttribute;

class Properties extends Base {

	public function callMethod($params) {
		switch ($params['method']) {
			case 'getUsed':
			break;
			case 'update':
				$id = $params['input_data']['LINK_ID'];
				unset($params['input_data']['LINK_ID']);
				return $this->update($id, ['SORT' => $params['input_data']['SORT']]);
			break;
			case 'delete':
				$result = $this->delete($params['input_data']);
				return $result;
			break;
			case 'add':
				$propertyId = $params['input_data']['PROPERTY_ID'];
				if (!$this->isUsedExists($propertyId)) {
					if(!$this->isUnusedExists($propertyId)) {
						$this->setError([
							'text' => 'Свойство не существует',
							'detail' => 'property_id='.$propertyId
						]);
					}
					$property = $this->getUnusedById($propertyId);
					$result = $this->add([
						'ID' => $property['ID'],
						'NAME' => $property['NAME']
					]);
					if ($this->hasError())
						return $result;
				}
				
				$sectionGroupId = $this->helperGetSectionGroupId([
					'GROUP_ID' => $params['input_data']['GROUP_ID'],
					'SECTION_ID'=> $params['input_data']['SECTION_ID']
				]);

				if($this->hasError())
					return $this->getError();

				$params['input_data'] = [
					'SECTION_GROUP_ID' => $sectionGroupId,
					'SORT' => $params['input_data']['SORT'],
					'PROPERTY_ID' => $propertyId
				];
				$result = $this->linkToPropertySectionGroup($params['input_data']);
					
				return $result;

			break;
		}
	}

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
	*	@param int $id
	*	@return array
	*/
	public function getUsedById($id) {
		$unfetchedResult = GruppenAttribute\PropertyTable::getList([
			'select' => ['*'],
			'filter' => ['ID' => $id]
		]);
		return $unfetchedResult->FetchAll();
	}

	/**
	*	@param int $id
	*	@return array
	*/
	public function getUnusedById($id) {
		$src = $this->getUnused(['filter'=>['ID'=>$id]]);
		return (isset($src[0])) ? $src[0] : $src;
	}

	/**
	*	@param int $id
	*	@return boolean
	*/
	public function isUsedExists($id) {
		return (count($this->getUsedById($id)) > 0) ? true : false;
	}

	/**
	*	@param int $id
	*	@return boolean
	*/
	public function isUnusedExists($id) {
		return (count($this->getUnusedById($id)) > 0) ? true : false;
	}

	/**
	*	@return array
	*/
	public function getUsed($inputParams) {
		#SELECT
		if(!array_key_exists('select', $inputParams)) {
			$params['select'] = [
					'P_ID' => 'PROPERTY.ID',
					'P_NAME' => 'PROPERTY.NAME',
					'LINK_ID' => 'ID',
					'SORT' => 'SORT',
			];
		}

		#FILTER
		if (array_key_exists('filter',$inputParams)) {
			$params['filter'] = $inputParams['filter'];
		}

		#ORDER
		$params['order'] = (array_key_exists('order', $inputParams)) ? $inputParam['order'] : ['SORT'];

		$unfetchedResult = GruppenAttribute\PropertySectionGroupTable::getList($params);

		$fetchedResult = [];
		while($property = $unfetchedResult->Fetch()) {

			$fetchedResult[] = [
				'ID' => $property['P_ID'],
				'NAME' => $property['P_NAME'],
				'LINK_ID' => $property['LINK_ID'],
				'SORT' => $property['SORT']
			];
		}

		return $fetchedResult;
	}

	/**
	*	@param int $id
	*	@return array
	*/
	public function getLinkedToSectionId($params) {
		return $this->getUsed([
			'filter' => [
				'SECTION_GROUP.GROUP_ID' => $params['GROUP_ID'],
				'SECTION_GROUP.SECTION_ID' => $params['SECTION_ID']
			]	
		]);
	}

	/**
	*	@param array $params {
	*		@option int "GROUP_ID"
	*		@option int "SECTION_ID"
	*	}
	*	@return int
	*/
	protected function getSectionGroupId($params) {
		$unfetchedResult = GruppenAttribute\SectionGroupTable::getList([
			'select' => ['ID'],
			'filter' => $params
		]);

		$fetchedResult = $unfetchedResult->FetchAll();

		return $fetchedResult[0]['ID'];
	}

	/**
	*	@param array $params {
	*		@option int "GROUP_ID"
	*		@option int "SECTION_ID"
	*	}
	*	@return array
	*/
	public function getUnlinkedToSectionId($params) {
		$linkedElements = $this->getLinkedToSectionId($params);

		$linkedIds = [];
		foreach($linkedElements as $linkedElement) {
			$linkedIds[] = $linkedElement['ID'];
		}
		if (count($linkedIds) == 0)
			$filter = '';
		else
			$filter = ['filter' => ['!@ID' => $linkedIds]]; 
		return $this->getUnused($filter);
	}

	/**
	*	@param array
	*	@return array
	*/
	public function getUnused($inputParams) {
		$params = [];
		$params['select'] = (isset($inputParams['select'])) ? $inputParams['select'] : ['ID', 'NAME'];
		if (array_key_exists('filter', $inputParams))
			$params['filter'] = $inputParams['filter'];
		if (!array_key_exists('order', $inputParams))
			$params['order'] = ['NAME'];

		$unfetchedResult = \Bitrix\Iblock\PropertyTable::getList($params);

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

		$unfetchedUnused = \Bitrix\Iblock\PropertyTable::getList(
			[
				'select' => ['ID', 'NAME'],
				'filter' => ['!=ID' => $used_id]
			]
		);
		return $unfetchedUnused->FetchAll();
	}
	
	/**
	*	@param int
	*	@return bool
	*/
	public function delete($params) {
		#todo откат rollback
		$this->setErrorFalse();
		$result = GruppenAttribute\PropertySectionGroupTable::delete($params);
		if(!$result->isSuccess())
			$this->setError([
				'text' => 'Ошибка при удалении привязки свойства',
				'detail' => $params
			]);
		
		return $this->getError();
	}


	/**
	*	@param array $params {
	*		@option int "ID_GROUP"
	*		@option int "IG_SECTION"
	*	}
	*	@return array|int
	*/
	protected function helperGetSectionGroupId($params) {
		$this->setErrorFalse();
		$unfetchedResult = GruppenAttribute\SectionGroupTable::getList([
			'select' => ['ID'],
			'filter' => $params
		]);
		$src_data = $unfetchedResult->FetchAll();
		
		if (!isset($src_data[0]['ID'])) {
			$this->setError([
				'text' => 'Раздел не прикреплён к группе',
				'detail' => $params
			]);
			return $this->result;
		}

		return $src_data[0]['ID']; 
	}

	/**
	*	@param array $params {
	*		@option int "SECTION_GROUP_ID"
	*		@option int "PROPERTY_ID"
	*		@option int "SORT"
	*	}
	*	@return array
	*/
	public function linkToPropertySectionGroup($params) {
		$this->setErrorFalse();
		
		if (!isset($params['SORT']) || (isset($params['SORT']) && $params['SORT'] === NULL))
			$params['SORT'] = 0;
			
		$result = GruppenAttribute\PropertySectionGroupTable::add($params);

		if ($result->isSuccess()) 
			$this->result['id'] = $result->getId();
		else
			$this->setError(['text' => $result->getErrorMessages(), 'detail' => $params]);

		return $this->result;
	}

	/**
	*	@param array $params {
	*		@option int "ID_GROUP"
	*		@option int "IG_SECTION"
	*	}
	*	@return boolean
	*/
	public function hasRelationWithSection($params) {
		$unfetchedResult = GruppenAttribute\PropertySectionTable::getList([
			'filter' => $params,
		]);

		return (count($unfetchedResult->FetchAll()) > 0) ? true : false;
	}
	
	public function add($params) {
		$this->setErrorFalse();
		$result = GruppenAttribute\PropertyTable::add($params);

		if ($result->isSuccess()) 
			$this->result['id'] = $result->getId();
		else 
			$this->setError(['text' => $result->getErrorMessages(), 'detail' => $params]);
		
		return $this->result;
	}
	
	/**
	*	@param int $id
	*	@param array $params
	*
	*	@return array $this->result
	*/
	public function update($id, $params) {
		$this->setErrorFalse();
		$result = GruppenAttribute\PropertySectionGroupTable::update($id,$params);

		if(!$result->isSuccess())
			$this->setError([
				'text' => $result->getErrorMessages(),
				'detail' => $params
			]);

		return $this->result;
	}
}
