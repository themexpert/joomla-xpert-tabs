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

abstract class modXpertTabsHelper
{

    public static function loadScripts($module, $params){
        $doc = JFactory::getDocument();

        // Set moduleid
        $module_id = XEFUtility::getModuleId($module, $params);

        // Load jQuery
        XEFUtility::addjQuery($module, $params);
        
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
            $doc->addScript(JURI::root(true).'/modules/mod_xperttabs/assets/js/xperttabs.js');
            define('XPERT_TABS',1);
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