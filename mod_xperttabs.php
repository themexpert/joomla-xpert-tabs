<?php
/**
 * @package Xpert Tabs
 * @version 2.1
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
 
// no direct access
defined('_JEXEC') or die('Restricted accessd');


// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$list = modXpertTabsHelper::getLists($params);
$module_id = 'xt'.$module->id;

modXpertTabsHelper::loadStyles($params);
modXpertTabsHelper::loadScripts($params, $module_id);

$content_source = $params->get('content_source','mods');

if($content_source == 'joomla' ) require( JModuleHelper::getLayoutPath('mod_xperttabs') );
else require( JModuleHelper::getLayoutPath('mod_xperttabs','modules') );
