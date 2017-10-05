<?
namespace Volex\GruppenAttribute;

use Volex\GruppenAttribute;

class Utilities {

	/**
	*  @param "params" array {
	*     @option "section_code" => string    | код раздела
	*     @option "iblock_id" => int|string   | id инфоблока
	*     @option "group_code" => string      | код группы в gruppenattribute
	*     @option "properties" => array       | массив свойств, формируемый битриксом
	*  }
	*/
	public static function groupProperties($params = []) {
		if (!isset($params['section_code']) ||
			!isset($params['iblock_id'])	||
			!isset($params['group_code']) ||
			!isset($params['properties']) ||
			!is_array($params['properties'])
		) {
			return false;
		}

		$group_id = GruppenAttribute\Utilities::gp_get_group_id_by_code($params['group_code']);
		$section_id = GruppenAttribute\Utilities::get_section_id_by_code([
			'section_code' => $params['section_code'],
			'iblock_id' => $params['iblock_id']
		]);
		$order = GruppenAttribute\Utilities::gp_get_order([
			'group_id' => $group_id,
			'section_id' => $section_id
		]);
		$copy_properties = GruppenAttribute\Utilities::gp_make_id_as_key($params['properties']);

		return GruppenAttribute\Utilities::gp_ordering(['properties' => $copy_properties, 'order' => $order]);
	}
  
  public static function getProperties($params = []) {
		if (!isset($params['section_code']) ||
			!isset($params['iblock_id'])	||
			!isset($params['group_code']) 
		) {
			return false;
		}

		$group_id = GruppenAttribute\Utilities::gp_get_group_id_by_code($params['group_code']);
		$section_id = GruppenAttribute\Utilities::get_section_id_by_code([
			'section_code' => $params['section_code'],
			'iblock_id' => $params['iblock_id']
		]);
		$order = GruppenAttribute\Utilities::gp_get_order([
			'group_id' => $group_id,
			'section_id' => $section_id
		]);

    return $order;
  }

	/**
	*  @param "group_code" string
	*  @return int|boolean  | id-группы или false
	*/
	private static function gp_get_group_id_by_code($group_code = '') {
		$groupEntity = new Groups;
		$group = $groupEntity->getByCode($group_code);

		return (isset($group[0]['ID'])) ? $group[0]['ID'] : false;
	}

	private static function gp_test_order($items = ['properties' => [], 'order' => []]) {
		foreach($items['order'] as $key => $item) {
			if($item != $items['properties'][$key]['ID'])
				return false;
		}

		return true;
	}

	private static function gp_get_order($items = ['group_id' => '', 'section_id' => '']) {
		$order = [];

		$propertyEntity = new Properties;
		$properties = $propertyEntity->getLinkedToSectionId(['GROUP_ID' => $items['group_id'], 'SECTION_ID' => $items['section_id']]);

		foreach($properties as $property) {
			$order[] = $property['ID'];
		}
		
		return $order;
	}

	/**
	* @return array {
	*   @option "properties" array | отсортирован по "order" array
  * }
	*/
	private static function gp_ordering($items = ['properties' => [], 'order' => []]) {
		$tmp = [];
		foreach($items['order'] as $property_id) {
      if ($items['properties'][$property_id])
			  $tmp[] = $items['properties'][$property_id];
		}

		return $tmp;
	}

	private static function gp_make_id_as_key($items = []) {
		$tmp = [];
		foreach($items as $item) {
			$tmp[$item['ID']] = $item;
		}
		
		return $tmp;
	}		 

	/**
	*  @param "params" array {
	*     @option "section_code" string    | code раздела
	*     @option "iblock_id" string|int   | id инфоблока
	*  }
	*  @return string|int|boolean          | id раздела или false
	*/
	private static function get_section_id_by_code($params = ['section_code' => '', 'iblock_id' => '']) {
		$section_id = \CIBlockFindTools::GetSectionID(
			"",
			$params['section_code'],
			[
				"GLOBAL_ACTIVE" => "Y",
				"IBLOCK_ID" => $params['iblock_id'],
			]
		);

		return ($section_id) ? $section_id : false;
	}

}
