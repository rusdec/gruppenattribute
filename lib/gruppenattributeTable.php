<?php

namespace Volex\GruppenAttribute;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Validator;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

final class TablePrefix {

	private static $prefix = 'volex_gruppenattribute_';

	public static function getPrefix() {
		return self::$prefix;
	}
}

/**
*
* Таблицы сущностей:
*	Инфоблоки
*	Группы
*	Разделы
*	Свойства
*
* Таблицы связей:
*	Разделы_к_Группам
*	Свойства_к_Разделам
*
* Связи:
*	Инфоблок	1:М	Группы	| через поле связи
*	Группы	М:М	Разделы	| через таблицу связей
*	Разделы	М:М	Свойства	| через таблицу связей
*
*/

/**
*	Таблицы сущностей
*/

#Инфоблоки
class IblockTable extends DataManager {
	public static function getTableName() {
		$prefix = \Volex\GruppenAttribute\TablePrefix::getPrefix();
		return $prefix.'iblocks';
	}

	public static function getMap() {
		return array(
			new IntegerField('ID', array(
				'autocomplate' => false,
				'primary' => true
			)),
			new StringField('NAME', array(
				'required' => true
			)),
			#Обратная связь Инфоблок - группы
			new ReferenceField('GROUP',
				'Volex\GruppenAttribute\Group',
				array('=this.ID' => 'ref.IBLOCK_ID')
			),
		);
	}
}

#Группы
class GroupTable extends DataManager {
	public static function getTableName() {
		$prefix = \Volex\GruppenAttribute\TablePrefix::getPrefix();
		return $prefix.'groups';
	}

	public static function getMap() {
		return array(
			new IntegerField('ID', array(
 				'autocomplete' => true,
				'primary' => true,
			)),
			new StringField('NAME', array(
				'required' => true,
			)),
			new StringField('CODE', array(#TODO
				'required' => true,
			)),
			#Инфоблок - группы
			new IntegerField('IBLOCK_ID', array(
				'autocpmplate' => false,
				'require' => true,
				'primary' => true
			)),

			#Инфоблок - группы
			new ReferenceField('IBLOCK',
				'Volex\GruppenAttribute\Iblock',
				array('=this.IBLOCK_ID' => 'ref.ID')
			),
				
		);
	}
}

#Разделы
class SectionTable extends DataManager {
	public static function getTableName() {
		$prefix = \Volex\GruppenAttribute\TablePrefix::getPrefix();
		return $prefix.'sections';
	}

	public static function getMap() {
		return array(
			new IntegerField('ID', array(
					'primary'		=> true,
			)),
			new StringField('NAME',	array(
					'require'	=> true
			)),

			#Связи
			new ReferenceField('SECTION_TO_GROUP',
				'Volex\GruppenAttribute\SectionGroup',
				array('=this.ID' => 'ref.SECTION_ID')
			),
			#Пример:
			#	SectionTable::getList['select' => ['PROPERTY_NAME' => 'SECTION_TO_PROPERTY.PROPERTY.NAME']]
			new ReferenceField('SECTION_TO_PROPERTY',
				'Volex\GruppenAttribute\PropertySection',
				array('=this.ID' => 'ref.SECTION_ID')
			)
		);
	}
}

#Свойства
class PropertyTable extends DataManager {
	public static function getTableName() {
		$prefix = \Volex\GruppenAttribute\TablePrefix::getPrefix();
		return $prefix.'properties'; 
	}

	public static function getMap() {
		return array(
			new IntegerField('ID', array(
				'primary' => true,
			)),
			new StringField('NAME',	array(
					'require'	=> true
			)),
			#Связь Свойство к Раздлелу
			#Пример:
			#	PropertyTable::getList['select' => ['SECTION_NAME' => 'PROPERTY_TO_SECTION.SECTION.NAME']]
			new ReferenceField('PROPERTY_TO_SECTION',
				'Volex\GruppenAttribute\PropertySection',
				array('=this.ID' => 'ref.PROPERTY_ID')
			)
		);
	}
}

/**
* Таблицы связей
*/

#Разделы_к_Группам
class SectionGroupTable extends DataManager {
	public static function getTableName() {
		$prefix = \Volex\GruppenAttribute\TablePrefix::getPrefix();
		return $prefix.'section_to_group';
	}

	public static function getMap() {
		return array(
			new IntegerField('GROUP_ID', array(
				'autocomplete' => false,
				'primary' => true,
			)),

			new IntegerField('SECTION_ID', array(
				'autocomplete' => false,
				'primary' => true,
			)),

			#Связи
			new ReferenceField('GROUP',
				'Volex\GruppenAttribute\Group',
				array('=this.GROUP_ID' => 'ref.ID')
			),
			new ReferenceField('SECTION',
				'Volex\GruppenAttribute\Section',
				array('=this.SECTION_ID' => 'ref.ID')
			)
		);
	}
}

#Свойства к Разделам
class PropertySectionTable extends DataManager {
	public static function getTableName() {
		$prefix = \Volex\GruppenAttribute\TablePrefix::getPrefix();
		return $prefix.'property_to_section';
	}

	public static function getMap() {
		return array(
			new IntegerField('SECTION_ID', array(
				'autocomplete' => false,
				'primary' => true,
			)),
			new IntegerField('PROPERTY_ID', array(
				'autocomplete' => false,
				'primary' => true,
			)),

			#Связи
			new ReferenceField('SECTION',
				'Volex\GruppenAttribute\Section',
				array('=this.SECTION_ID' => 'ref.ID')
			),
			new ReferenceField('PROPERTY',
				'Volex\GruppenAttribute\Property',
				array('=this.PROPERTY_ID' => 'ref.ID')
			)
		);
	}
}
