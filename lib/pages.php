<?
namespace Volex\GruppenAttribute;

use \Bitrix\Iblock;
use Volex\GruppenAttribute;

class IBlocks {
	
	/**
	*	@return array
	*/
	public static function getAll() {
		return array_merge(self::getRegistered(), self::getFree());
	}

	/**
	*	@return array
	*/
	public static function getRegistered() {
		$unfetchedRegistered = GruppenAttribute\IBlockTable::getList(
			[
				'select' => ['ID', 'NAME']
			]
		);
		return $unfetchedRegistered->FetchAll();
	}

	/**
	*	@return array
	*/
	public static function getFree() {
		$registeredTmp = self::getRegistered();
		$registered = [];
		foreach($registeredTmp as $tmp) {
			$registered[] = $tmp['ID'];
		}
		$unfetchedFree = Iblock\IblockTable::getList(
			[
				'select' => ['ID', 'NAME'],
				'filter' => ['!=ID' => $registered]
			]
		);

		return $unfetchedFree->FetchAll();
	}

}

class Groups {
	
	protected $iblockID;

	function __construct($iblockID) {
		$this->iblockID = $iblockID;
	}

	/**
	*
	*	@return array
	*
	*/
	public function getAll() {
		$unfetchedGroups = GroupTable::getList(
			[
				'select' => ['*']
			]
		);

		return $unfetchedGroups->FetchAll();
	}

	/**
	*
	*	@return array
	*
	*/
	public function getAllForIblock($id) {
		$unfetchedGroups = GroupTable::getList(
			[
				'select' => ['*'],
				'filter' => ['IBLOCK.IBLOCK_ID' => $id]
			]
		);
		return $unfetchedGroups->FetchAll();
	}
}
