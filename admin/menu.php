<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$startLevel = 'groups';

$aMenu = [
	[
		'parent_menu'	=> 'global_menu_content',
		'sort'		=> 400,
		'text'		=> "Группа свойств",
		'title'		=> "Группы свойств",
		'url'			=> 'gruppenattribute_index.php?level='.$startLevel,
		'items_id'	=> 'menu_references',
		/*
		'items' => [
			[
				'text'	=> "Группы",
				'url'		=> 'gruppenattribute_index.php?level=paramval&lang='.LANGUAGE_ID,
				'more_url'	=> array('gruppenattribute_index.php?param1=paramval&lang='.LANGUAGE_ID),
				'title'		=> "Группы",
			]
		]
		*/
	]
];

return $aMenu;
