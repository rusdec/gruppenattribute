<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Volex\GruppenAttribute as vGA;

Loc::loadMessages(__FILE__);

if (class_exists('GruppenAttribute')) {
    return;
}

class GruppenAttribute extends CModule
{
    /** @var string */
    public $MODULE_ID;

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $MODULE_GROUP_RIGHTS;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    public function __construct() {
        $this->MODULE_ID = 'gruppenattribute';
        $this->MODULE_VERSION = '0.0.2';
        $this->MODULE_VERSION_DATE = date('Y-m-d G:i');
        $this->MODULE_NAME = Loc::getMessage('MODULE_GRUPPENATTRIBUTE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_GRUPPENATTRIBUTE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = "Volex";
        $this->PARTNER_URI = "http://volex.su";
    }

    public function doInstall() {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
		  #$this->installDemoData();
    }

    public function doUninstall() {
        $this->uninstallDB();
        ModuleManager::unregisterModule($this->MODULE_ID);
    }

    public function installDB() {
        if (Loader::includeModule($this->MODULE_ID)) {
           vGA\IblockTable::getEntity()->createDbTable();
           vGA\GroupTable::getEntity()->createDbTable();
           vGA\SectionTable::getEntity()->createDbTable();
           vGA\PropertyTable::getEntity()->createDbTable();
           vGA\SectionGroupTable::getEntity()->createDbTable();
     #      vGA\PropertySectionTable::getEntity()->createDbTable();
           vGA\PropertySectionGroupTable::getEntity()->createDbTable();
        }
    }

    public function uninstallDB() {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getInstance()->getConnection();
            $connection->dropTable(vGA\IblockTable::getTableName());
            $connection->dropTable(vGA\GroupTable::getTableName());
            $connection->dropTable(vGA\SectionTable::getTableName());
            $connection->dropTable(vGA\PropertyTable::getTableName());
            $connection->dropTable(vGA\SectionGroupTable::getTableName());
            #$connection->dropTable(vGA\PropertySectionTable::getTableName());
            $connection->dropTable(vGA\PropertySectionGroupTable::getTableName());
        }
    }

	 private function installDemoData() {
	 		$iblock = [
				'NAME' => 'Каталог товаров',
				'ID'	=> 8
			];

			$groups		= [
				[
					'NAME' => 'Группа1',
					'CODE' => 'Grupa1'
				],
				[
					'NAME' => 'Группа2',
					'CODE' => 'Grupa2'
				]
			];

			$sections	= array(
				'Раздел1',
				'Раздел2',
				'Раздел3',
			);

			$properties = array(
				'Свойтсво1',
				'Свойтсво2',
				'Свойтсво3',
				'Свойтсво4',
				'Свойтсво5'
			);

			$iblock_group = array(
				$iblock_id => '1',
				$iblock_id => '2'
			);

			$group_section = array(
				'1'=>'1',
				'1'=>'2',
				'2'=>'3' 
			);

			$section_property = array(
				'1'=>'1',
				'1'=>'2',
				'1'=>'3',
				'2'=>'4',
				'3'=>'5'
			);

		
	 		vGA\IblockTable::add(array(
				'ID' => $iblock['ID'],
				'NAME' => $iblock['NAME']
			));

			foreach($groups as $group) {
				vGA\GroupTable::add(array(
					'NAME' => $group['NAME'],
					'CODE' => $group['CODE'],
					'IBLOCK_ID' => $iblock['ID']
				));
			}

			foreach($sections as $section) {
				vGA\SectionTable::add(array(
					'NAME' => $section
				));
			}

			foreach($properties as $property) {
				vGA\PropertyTable::add(array(
					'NAME' => $property
				));
			}

			foreach($group_section as $group_id => $section_id) {
				vGA\SectionGroupTable::add(array(
					'SECTION_ID'	=> $group_id,
					'GROUP_ID'		=> $section_id
				));
			}

			foreach($section_property as $section_id => $property_id) {
				vGA\PropertySectionTable::add(array(
					'SECTION_ID'	=> $section_id,
					'PROPERTY_ID'	=> $property_id
				));
			}
	 }
}
