<?
namespace Volex\GruppenAttribute;

use \Bitrix\Iblock;
use Volex\GruppenAttribute as VGA;

final class Factory {

	public static function instance($class) {
		switch ($class) {
			case 'groups' : return new VGA\Groups;
			break;
			case 'iblocks' : return new VGA\Iblocks;
			break;
		}
	}

}
