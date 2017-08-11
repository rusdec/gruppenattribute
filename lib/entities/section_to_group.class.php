<?
namespace Volex\GruppenAttribute;

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('main');

use Volex\GruppenAttribute;

class SectionsGroups extends Base {

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
	public static function get($params) {
		$params['select'] = (isset($inputParams['select'])) ? $inputParams['select'] : ['*'];
		if (isset($inputParams['filter']))
			$params['filter'] = $inputParams['filter'];

		$unfetchedResult = GruppenAttribute\SectionGroupTable::getList($params);

		return $unfetchedResult->FetchAll();
	}

	/**
	*	@return array
	*/
	public static function getAll() {
		return GruppenAttribute\SectionsGroups::get();
	}

	/**
	*	@param int $id
	*	@return array
	*/
	public static function getById($id) {
		$src = GruppenAttribute\SectionsGroups::get(['filter'=>['ID'=>$id]]);
		return (isset($src[0])) ? $src[0] : $src;
	}

	/**
	* todo	
	*/
	public static function getByGroupId($id) {
		return GruppenAttribute\SectionsGroups::get(['filter'=>['GROUP_ID'=>$id]]);
	}

	/**
	* todo	
	*/
	public static function getByGroupSectionId($params) {
		return GruppenAttribute\SectionsGroups::get(['filter'=>[$params]]);
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
		return GruppenAttribute\SectionGroupTable::delete($params);
	}

	/**
	*	@param array $params {
	*		@option int "GROUP_ID"
	*		@option int "SECTION_ID"
	*	}
	*	
	*	@return array "$this->result"
	*/
	public function add($params) {
		$this->setErrorFalse();
		if(count(GruppenAttribute\SectionsGroups::getByGroupSectionId([
			'SECTION_ID' => $params['SECTION_ID'],
			'GROUP_ID' => $params['GROUP_ID']
		])) > 0) {
			$this->setError(['text' => $params['SECTION_ID'].' уже прикреплён к группе '.$params['GROUP_ID'], 'detail' => '']);
			return $this->result;
		}

		$result = GruppenAttribute\SectionGroupTable::add($params);

		if ($result->isSuccess()) 
			$this->result['id'] = $result->getId();
		else 
			$this->setError(['text' => $result->getErrorMessages(), 'detail' => $params]);
		
		return $this->result;
	}

	/**
	*	@param array "params" {
	*		@option int "FROM_GROUP_ID"
	*		@option int "TO_GROUP_ID"
	*	}
	*/
 	public function copyAllSectionsFromGroup($params) {
		$fromData = GruppenAttribute\SectionsGroups::getByGroupId($params['FROM_GROUP_ID']);
		foreach($fromData as $data) {
			echo "<pre>"."insert into TABLE (GROUP_ID, SECTION_ID) VALUES (".$params['TO_GROUP_ID'].",".$data['SECTION_ID'].")"."</pre>";
			$res = GruppenAttribute\SectionsGroups::add(['GROUP_ID' => $params['TO_GROUP_ID'], 'SECTION_ID' => $data['SECTION_ID']]);
			echo "<pre>"."<p>Результат:".$res['has_error']."</p>"."<p>".$res['messages']['text']."</p>"."</pre>";
		}
		
	}
}
