<?
namespace Volex\GruppenAttribute;

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('main');

use Volex\GruppenAttribute;

class PropertiesSectionsGroups extends Base {

	public function callMethod($params) {
		switch ($params['method']) {
			case 'get':
			break;
			case 'add':
			break;
			case 'delete':
			break;
		}
	}

	/**
	*	@return array
	*/
	public static function get($inputParams) {
		$params['select'] = (isset($inputParams['select'])) ? $inputParams['select'] : ['*'];
		if (isset($inputParams['filter']))
			$params['filter'] = $inputParams['filter'];

		$unfetchedResult = GruppenAttribute\PropertySectionGroupTable::getList($params);

		return $unfetchedResult->FetchAll();
	}

	/**
	*	@return array
	*/
	public static function getAll() {
		return GruppenAttribute\PropertiesSectionsGroups::get();
	}

	/**
	*	@param int $id
	*	@return array
	*/
	public static function getById($id) {
		$src = GruppenAttribute\PropertiesSectionsGroups::get(['filter'=>['ID'=>$id]]);
		return (isset($src[0])) ? $src[0] : $src;
	}

	/**
	* todo	
	*/
	public static function getByPropertyId($id) {
		return GruppenAttribute\PropertiesSectionsGroups::get(['filter'=>['PROPERTY_ID'=>$id]]);
	}

	/**
	* todo	
	*/
	public static function getBySectionGroupId($id) {
		return GruppenAttribute\PropertiesSectionsGroups::get(['filter'=>['SECTION_GROUP_ID' => $id]]);
	}

	/**
	* todo	
	*/
	public static function getByPropertySectionGroupId($params) {
		return GruppenAttribute\PropertiesSectionsGroups::get([
			'filter' => [
				'SECTION_GROUP_ID' => $params['SECTION_GROUP_ID'],
				'PROPERTY_ID'=>$params['PROPERTY_ID']
			]
		]);
	}

	/**
	*	@param array $params {
	*		@option int "SECTION_ID"
	*		@option int "GROUP_ID"
	*	}
	*
	*	@return bool
	*/
	public function delete($params) {
		return GruppenAttribute\PropertySectionGroupTable::delete($params);
	}

	/**
	*	@param array $params {
	*		@option int "SECTION_GROUP_ID"
	*		@option int "PROPERTY_ID"
	*		@option int "SORT"
	*	}
	*	
	*	@return array "$this->result"
	*/
	public function add($params) {
		$this->setErrorFalse();
		if (count(GruppenAttribute\PropertiesSectionsGroups::getByPropertySectionGroupId([
			'SECTION_GROUP_ID' => $params['SECTION_GROUP_ID'],
			'PROPERTY_ID' => $params['PROPERTY_ID']
		])) > 0) {
			$this->setError(['text' => $params['PROPERTY_ID'].' уже прикреплён к разделу '.$params['SECTION_GROUP_ID'], 'detail' => $params]);
			return $this->result;
		}

		$result = GruppenAttribute\PropertySectionGroupTable::add($params);

		if ($result->isSuccess()) 
			$this->result['id'] = $result->getId();
		else 
			$this->setError(['text' => $result->getErrorMessages(), 'detail' => $params]);
		
		return $this->result;
	}

	/**
	*	@param array $params {
	*		@option int "FROM_SECTION_GROUP_ID"
	*		@option int "TO_SECTION_GROUP_ID"
	*	}
	*/
	public function copyAllPropertiesFromSectionGroup($params) {
		$fromData = GruppenAttribute\PropertiesSectionsGroups::getBySectionGroupId($params['FROM_SECTION_GROUP_ID']);
		foreach($fromData as $data) {
			$res = GruppenAttribute\PropertiesSectionsGroups::add([
				'SECTION_GROUP_ID' => $params['TO_SECTION_GROUP_ID'],
				'PROPERTY_ID' => $data['PROPERTY_ID'],
				'SORT' => $data['SORT']
			]);
		}
	}
}
