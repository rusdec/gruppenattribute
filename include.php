<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

Loader::registerAutoLoadClasses('gruppenattribute', array(

	'Volex\GruppenAttribute\IblockTable'				=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\GroupTable'					=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\SectionTable'				=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\PropertyTable'				=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\SectionGroupTable'		=> 'lib/gruppenattributeTable.php',
	'Volex\GruppenAttribute\PropertySectionTable'	=> 'lib/gruppenattributeTable.php',
	
	'Volex\GruppenAttribute\IBlocks'						=> 'lib/pages.php',
	'Volex\GruppenAttribute\Groups'						=> 'lib/pages.php',

	 'Volex\GruppenAttribute\ApiStructure'				=> 'lib/api_structure.php',
	 'Volex\GruppenAttribute\ApiRequestCheck'			=> 'lib/api_request_checker.php'
));

EventManager::getInstance()->addEventHandler('main', 'OnAfterUserAdd', function(){
});
