<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

Loader::registerAutoLoadClasses('gruppenattribute', array(

	'Volex\GruppenAttribute\GroupTable'					=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\IblockTable'				=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\SectionTable'				=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\PropertyTable'				=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\SectionGroupTable'		=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\PropertySectionTable'	=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\PropertySectionGroupTable'	=> 'lib/gruppenattributeTable.php',
		
	'Volex\GruppenAttribute\Base'							=> 'lib/entities/base.class.php',
	'Volex\GruppenAttribute\Factory'						=> 'lib/entities/factory.class.php',

	'Volex\GruppenAttribute\Groups'						=> 'lib/entities/groups.class.php',
	'Volex\GruppenAttribute\Iblocks'						=> 'lib/entities/iblocks.class.php',
	'Volex\GruppenAttribute\Sections'					=> 'lib/entities/sections.class.php',
	'Volex\GruppenAttribute\Properties'					=> 'lib/entities/properties.class.php',
	'Volex\GruppenAttribute\SectionsGroups'			=> 'lib/entities/section_to_group.class.php',
	'Volex\GruppenAttribute\PropertiesSectionsGroups'	=> 'lib/entities/property_to_section_group.class.php',


	'Volex\GruppenAttribute\ApiStructure'				=> 'lib/apiStructure.class.php',
	'Volex\GruppenAttribute\ApiRequestCheck'			=> 'lib/apiRequestCheck.class.php'
));

EventManager::getInstance()->addEventHandler('main', 'OnAfterUserAdd', function(){
});
