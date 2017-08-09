<?
namespace Volex\GruppenAttribute;

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('main');

use Volex\GruppenAttribute;

class Properties extends Base {

	public function callMethod($params) {
		switch ($params['method']) {
			case 'getUsed':
				return $this->getUsed();
			break;
			case 'update':
			break;
			case 'add':
				#Раздел
				file_put_contents($_SERVER['DOCUMENT_ROOT'].'/sect.log', print_r($params, true));
				$sectionId = $params['input_data']['SECTION_ID'];
				$sectionEntity = new GruppenAttribute\Sections;
				#раздел не существует?
				if(!$sectionEntity->isUnusedExists($sectionId)) {
					#вернуть ошибку
					$this->setError([
						'text' => 'Раздел не существует',
						'detail' => 'id='.$section
					]);
					return $this->getError();
				}
				#раздел не в используемом?
				$section = $sectionEntity->getUnusedById($sectionId);
				if(!$sectionEntity->isUsedExists($sectionId)) {
					$result = $sectionEntity->add(['ID' => $section['ID'], 'NAME' => $section['NAME']]);
					if($result['has_error'])
						return $result;
				}

				#Свойство
				$propertyId = $params['input_data']['PROPERTY_ID'];

					#свойство не существует?
				if(!$this->isUnusedExists($propertyId)) {
					$this->setError([
						'text' => 'Свойство не существует',
						'detail' => 'id='.$propertyId
					]);
					return $this->getError();
				}
				$property = $this->getUnusedById($propertyId);

					#свойство не в используемых?
				if(!$this->isUsedExists($propertyId)) {
					#добавить
					$result = $this->add(['ID' => $propertyId, 'NAME' => $property['NAME']]);
					if ($result['has_error'])
						return $result;
				}
				$property = $this->getUsedById($propertyId);

					#раздел уже связан с группой?
				if($this->hasRelationWithSection(['SECTION_ID' => $sectionId, 'PROPERTY_ID' => $propertyId])) {
					$this->setError([
						'text' => 'Уже связаны',
						'detail' => 'section_id='.$sectionId.' <-> '.'property_id='.$propertyId
					]);
					return $this->getError();
				}
				
				$sort = (array_key_exists('SORT', $params['input_data'])) ? $params['input_data']['SORT'] : 0;
				$result = $this->linkToSection(['SECTION_ID' => $section['ID'], 'PROPERTY_ID' => $property['ID'], 'SORT' => $sort]);

				return $result;

			break;
			case 'delete':
				file_put_contents($_SERVER['DOCUMENT_ROOT'].'/prop.log', print_r($params, true));
				return $this->delete($params['input_data']);
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
		$src = $this->getUsed(['filter'=>['ID'=>$id]]);
		return (isset($src[0])) ? $src[0] : $src;
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
		$params['select'] = (isset($inputParams['select'])) ? $inputParams['select'] : ['*'];
		if (isset($inputParams['filter']))
			$params['filter'] = $inputParams['filter'];
		if (isset($inputParams['order']))
			$params['order'] = $inputParams['order'];

		$unfetchedResult = GruppenAttribute\PropertyTable::getList($params);

		return $unfetchedResult->FetchAll();
	}

	/**
	*	@param int $id
	*	@return array
	*/
	public function getLinkedToSectionId($id) {
		return $this->getUsed([
			'select'	=> ['SORT'=>'PROPERTY_TO_SECTION.SORT', 'NAME', 'ID'],
			'filter'	=> ['PROPERTY_TO_SECTION.SECTION_ID' => $id],
			'order'	=> ['SORT']	
		]);
	}

	/**
	*	@param int $id
	*	@return array
	*/
	public function getUnlinkedToSectionId($id) {
		$used_id = $this->getUsed(['select' => ['ID'], 'filter' => ['PROPERTY_TO_SECTION.SECTION_ID' => $id]]);
		$ids = [];
		foreach($used_id as $id) {
			$ids[] = $id['ID'];
		}
		if (count($ids) == 0)
			$filter = '';
		else
			$filter = ['filter' => ['!@ID' => $ids]]; 

		return $this->getUnused($filter);
	}

	/**
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
		return GruppenAttribute\PropertySectionTable::delete([
			'SECTION_ID' => $params['SECTION_ID'],
			'PROPERTY_ID'	=> $params['PROPERTY_ID']
		]);
	}

	/**
	*	@param array $params {
	*		@option int "ID_GROUP"
	*		@option int "IG_SECTION"
	*	}
	*	@return boolean
	*/
	public function linkToSection($params) {
		$this->setErrorFalse();
		$result = GruppenAttribute\PropertySectionTable::add($params);

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

}
