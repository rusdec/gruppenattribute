<?
namespace Volex\GruppenAttribute;

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('main');

use Volex\GruppenAttribute;

class Sections extends Base {

	public function callMethod($params) {
		switch ($params['method']) {
			case 'getUsed':
				return $this->getUsed();
			break;
			case 'add':
				#Группа
				file_put_contents($_SERVER['DOCUMENT_ROOT'].'/sect.log', print_r($params, true));
				$groupId = $params['input_data']['GROUP_ID'];
				$groupEntity = new GruppenAttribute\Groups;
				$group = $groupEntity->getById($groupId);
				#группа не существует?
				if (count($group) == 0) {
					#вернуть ошибку
					$this->setError([
						'text' => 'Группы не существует',
						'detail' => 'id='.$groupId
					]);
					return $this->getError();
				}


				#Раздел
				$sectionId = $params['input_data']['SECTION_ID'];

					#раздел не существует?
				if(!$this->isUnusedExists($sectionId)) {
					$this->setError([
						'text' => 'Раздел не существует',
						'detail' => 'id='.$sectionId
					]);
					return $this->getError();
				}
				$section = $this->getUnusedById($sectionId);

					#раздел не в используемых?
				if(!$this->isUsedExists($sectionId)) {
					#добавить
					$result = $this->add(['ID' => $sectionId, 'NAME' => $section['NAME']]);
					if ($result['has_error'])
						return $result;
				}
				$section = $this->getUsedById($sectionId);

					#раздел уже связан с группой?
				if($this->hasRelationWithGroup(['SECTION_ID' => $sectionId, 'GROUP_ID' => $groupId])) {
					$this->setError([
						'text' => 'Уже связаны',
						'detail' => 'section_id='.$sectionId.' <-> '.'group_id='.$groupId
					]);
					return $this->getError();
				}

				$result = $this->linkToGroup(['SECTION_ID' => $section['ID'], 'GROUP_ID' => $group['ID']]);

				return $result;

			break;
			case 'delete':
				file_put_contents($_SERVER['DOCUMENT_ROOT'].'/sect.log', print_r($params, true));
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

		$unfetchedResult = GruppenAttribute\SectionTable::getList($params);

		return $unfetchedResult->FetchAll();
	}

	/**
	*	@param int $id
	*	@return array
	*/
	public function getLinkedToGroupId($id) {
		return $this->getUsed(['filter' => ['SECTION_TO_GROUP.GROUP_ID' => $id]]);
	}

	/**
	*	@param int $id
	*	@return array
	*/
	public function getUnlinkedToGroupId($id) {
		$used_id = $this->getUsed(['select' => ['ID'], 'filter' => ['SECTION_TO_GROUP.GROUP_ID' => $id]]);
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
		
		$unfetchedResult = \Bitrix\Iblock\SectionTable::getList($params);

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

		$unfetchedUnused = \Bitrix\Iblock\SectionTable::getList(
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
		return GruppenAttribute\SectionGroupTable::delete([
			'SECTION_ID' => $params['SECTION_ID'],
			'GROUP_ID'	=> $params['GROUP_ID']
		]);
	}

	/**
	*	@param array $params {
	*		@option int "ID_GROUP"
	*		@option int "IG_SECTION"
	*	}
	*	@return boolean
	*/
	public function linkToGroup($params) {
		$this->setErrorFalse();
		$result = GruppenAttribute\SectionGroupTable::add($params);

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
	public function hasRelationWithGroup($params) {
		$unfetchedResult = GruppenAttribute\SectionGroupTable::getList([
			'filter' => $params,
		]);

		return (count($unfetchedResult->FetchAll()) > 0) ? true : false;
	}
	
	public function add($params) {
		$this->setErrorFalse();
		$result = GruppenAttribute\SectionTable::add($params);

		if ($result->isSuccess()) 
			$this->result['id'] = $result->getId();
		else 
			$this->setError(['text' => $result->getErrorMessages(), 'detail' => $params]);
		
		return $this->result;
	}

}
