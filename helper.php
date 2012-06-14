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
defined( '_JEXEC' ) or die('Restricted access');

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

jimport('joomla.application.component.model');

JModel::addIncludePath(JPATH_SITE.'/components/com_content/models');

abstract class modXpertTabsHelper{

    public static function getLists(&$params){
        //joomla specific
        if((string)$params->get('content_source') == 'joomla'){
            // Get the dbo
            $db = JFactory::getDbo();

            // Get an instance of the generic articles model
            $model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

            // Set application parameters in model
            $app = JFactory::getApplication();
            $appParams = $app->getParams();
            $model->setState('params', $appParams);

            // Set the filters based on the module params
            $model->setState('list.start', 0);
            $model->setState('list.limit', (int) $params->get('count', 5));
            $model->setState('filter.published', 1);

            // Access filter
            $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
            $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
            $model->setState('filter.access', $access);

            // Category filter
            $model->setState('filter.category_id', $params->get('catid', array()));

            // User filter
            $userId = JFactory::getUser()->get('id');
            switch ($params->get('user_id'))
            {
                    case 'by_me':
                            $model->setState('filter.author_id', (int) $userId);
                            break;
                    case 'not_me':
                            $model->setState('filter.author_id', $userId);
                            $model->setState('filter.author_id.include', false);
                            break;

                    case '0':
                            break;

                    default:
                            $model->setState('filter.author_id', (int) $params->get('user_id'));
                            break;
            }
            // Filter by language
            $model->setState('filter.language',$app->getLanguageFilter());

            //  Featured switch
            switch ($params->get('show_featured'))
            {
                    case '1':
                            $model->setState('filter.featured', 'only');
                            break;
                    case '0':
                            $model->setState('filter.featured', 'hide');
                            break;
                    default:
                            $model->setState('filter.featured', 'show');
                            break;
            }

            // Set ordering
            $order_map = array(
                    'm_dsc' => 'a.modified DESC, a.created',
                    'mc_dsc' => 'CASE WHEN (a.modified = '.$db->quote($db->getNullDate()).') THEN a.created ELSE a.modified END',
                    'c_dsc' => 'a.created',
                    'p_dsc' => 'a.publish_up',
            );
            $ordering = JArrayHelper::getValue($order_map, $params->get('ordering'), 'a.publish_up');
            $dir = 'DESC';

            $model->setState('list.ordering', $ordering);
            $model->setState('list.direction', $dir);
            $items = $model->getItems();

           foreach ($items as &$item) {
                $item->slug = $item->id.':'.$item->alias;
                $item->catslug = $item->catid.':'.$item->category_alias;

                if ($access || in_array($item->access, $authorised))
                {
                        // We know that user has the privilege to view the article
                        $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
                }
                else {
                        $item->link = JRoute::_('index.php?option=com_user&view=login');
                }

                $item->introtext = JHtml::_('content.prepare', $item->introtext);

            }

            return $items;

        }else{
            //module specific
            $mods = $params->get('modules');
            $options 	= array('style' => 'none');
            $items = array();

            for ($i=0;$i<count($mods);$i++) {
                $items[$i]->order 	= modXpertTabsHelper::getModule($mods[$i])->ordering;
                $items[$i]->title 	= modXpertTabsHelper::getModule($mods[$i])->title;
                $items[$i]->content = $items[$i]->introtext = JModuleHelper::renderModule(  modXpertTabsHelper::getModule($mods[$i]), $options);
		    }

		    return $items;

        }


    }
    //fetch module by id
    public static function getModule( $id ){

		$db		=& JFactory::getDBO();
		$where = ' AND ( m.id='.$id.' ) ';

		$query = 'SELECT *'.
			' FROM #__modules AS m'.
			' WHERE m.client_id = 0'.
			$where.
			' ORDER BY ordering'.
			' LIMIT 1';

		$db->setQuery( $query );
		$module = $db->loadObject();

		if (!$module) return null;

		$file				= $module->module;
		$custom				= substr($file, 0, 4) == 'mod_' ?  0 : 1;
		$module->user		= $custom;
		$module->name		= $custom ? $module->title : substr($file, 4);
		$module->style		= null;
		$module->position	= strtolower($module->position);
		$clean[$module->id]	= $module;

		return $module;
	}


    public static function loadScripts($params, $module_id){
        $doc =& JFactory::getDocument();
        //load jquery first
        modXpertTabsHelper::loadJquery($params);
        
        $effect         = "'". $params->get('transition_type','default'). "'";
        $fadein_speed   = (int)$params->get('fadein_speed',500);
        $fadeout_speed  = (int)$params->get('fadeout_speed',0);
        $auto_play      = ( (int)$params->get('auto_play',0) ) ? 'true' : 'false';
        $auto_pause     = ( (int)$params->get('auto_pause',1) ) ? 'true' : 'false';
        $event          = "'". $params->get('tabs_interaction','click'). "'";

        //scrollable js settings.
        if($params->get('tabs_scrollable')){
            $scroll = ".slideshow({autoplay: {$auto_play},autopause: {$auto_pause}})";
        }else{
            $scroll = '';
        }
        if((int)$auto_play){
            $rotate = 'rotate: true,';
        }else{
            $rotate = '';
        }

        $js = "
            jQuery.noConflict();
            jQuery(document).ready(function(){
                jQuery('#{$module_id} .xt-nav ul').tabs('#{$module_id}-pans > .xt-pane',{
                    effect: {$effect},
                    fadeInSpeed: {$fadein_speed},
                    fadeOutSpeed: {$fadeout_speed},
                    {$rotate}
                    event: {$event}
                }){$scroll};
            });
        ";
        $doc->addScriptDeclaration($js);

        if(!defined('XPERT_TABS')){
            //add tab engine js file
            $doc->addScript(JURI::root(true).'/modules/mod_xperttabs/tmpl/xperttabs.js');
            define('XPERT_TABS',1);
        }
    }


    public static function loadJquery($params){
        $doc =& JFactory::getDocument();    //document object
        $app =& JFactory::getApplication(); //application object

        static $jqLoaded;

        if ($jqLoaded) {
            return;
        }

        if($params->get('load_jquery') AND !$app->get('jQuery')){
            //get the cdn
            $cdn = $params->get('jquery_source');
            switch ($cdn){
                case 'google_cdn':
                    $file = 'https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js';
                    break;
                case 'local':
                    $file = JURI::root(true).'/modules/mod_xperttabs/tmpl/jquery-1.6.1.min.js';
                    break;
            }
            $app->set('jQuery',1.6);
            $doc->addScript($file);
            //$doc->addScriptDeclaration("jQuery.noConflict();");
            $jqLoaded = TRUE;
        }

    }


    public static function loadStyles($params){
        $app        = &JApplication::getInstance('site', array(), 'J');
        $template   = $app->getTemplate();
        $doc        =& JFactory::getDocument();

        //Load stylesheets
        if($params->get('style') !== 'custom' )
        {
            $style_path = JURI::root(true).'/modules/mod_xperttabs/styles/';
            $style_selected = $params->get('style','style1');
            $style_file_name = $style_selected . '.css';

            $doc->addStyleSheet($style_path . $style_selected . '/' . $style_file_name);
        }else{
            if (file_exists(JPATH_SITE.DS.'templates'.DS.$template.'/css/xperttabs.css'))
            {
               $doc->addStyleSheet(JURI::root(true).'/templates/'.$template.'/css/xperttabs.css');
            }
        }
    }


    public static function generateTabs($tabs, $list, $params){
        $title_type = $params->get('tabs_title_type');
        $tab_scrollable = $params->get('tabs_scrollable');
        $position = $params->get('tabs_position','top');

        if($title_type == 'custom'){
            $titles = explode(",",$params->get('tabs_title_custom'));
        }

        if($tabs == 0 OR $tabs>count($list)) $tabs = count($list);

        $html  = "<div class='xt-nav $position'>";
        if($params->get('tabs_scrollable')) $html .= "<a class='backward'>backward</a>\n";
        $html .= "<ul>";

        for($i=0; $i<$tabs; $i++){
            $class = '';
            if($list[$i]->introtext != NULL){
                if(!$i) $class= 'first';
                if($i == $tabs - 1) $class= 'last';

                if($title_type == 'custom') $title = (isset($titles[$i])) ? $titles[$i] : '';
                else $title = $list[$i]->title;

                $html .= "<li class='$class' ><a href=\"#\"><span>$title</span></a></li>\n";

            }

        }
        $html .= "</ul>\n";
        if($params->get('tabs_scrollable')) $html .= "<a class='forward'>forward</a>\n";
        $html .= "<div class='clear'></div>";
        $html .= "</div> <!--xt-nav end-->\n";

        return $html;
        
    }

}