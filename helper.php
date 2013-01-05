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
                jQuery('#{$module_id}').scrollable({
                    items : '.xt-items',
                    next: '.xt-next',
                    prev: '.xt-prev'
                }).navigator({
                        navi: '.xt-nav',
                        naviItem: 'a',
                        activeClass: 'current'
                });
            });
        ";
        $doc->addScriptDeclaration($js);

       /* if( !defined('XPERT_SCROLLER'))
        {
            //add tab engine js file
            $doc->addScript( JURI::root(true).'/modules/mod_xperttabs/assets/js/xpertscroller.js' );
            define('XPERT_SCROLLER', 1);
        }*/

        if(!defined('XPERT_TABS'))
        {
            $doc->addScript(JURI::root(true).'/modules/mod_xperttabs/assets/js/tabs.js');
            define('XPERT_TABS', 1);
        }
    }


    public static function generateTabs($tabs, $list, $params){
        $title_type = $params->get('tabs_title_type');
        $position = $params->get('tabs_position','top');
        $html = array();

        if($title_type == 'custom'){
            $titles = explode(",", $params->get('tabs_title_custom'));
        }

        if($tabs == 0 OR $tabs>count($list)) $tabs = count($list);

        $html[] = '<ul class="xt-nav '. $position .'">';

        for($i=0; $i<$tabs; $i++){

            if($list[$i]->introtext != NULL)
            {
                // li and a classes
                $class = '';
                $aclass = '';

                if(!$i){
                    $class  = 'first';
                    $aclass = 'active';
                }
                if($i == $tabs - 1) $class= 'last';

                if($title_type == 'custom') $title = (isset($titles[$i])) ? $titles[$i] : '';
                else $title = $list[$i]->title;

                $html[] = '<li class="'. $class .'">';
                    $html[] = '<a class="'. $aclass .'" data-toggle="tab" data-target="#txtabs-'.$i.'">';
                        $html[] = "<span>$title</span>";
                    $html[] = '</a>';
                $html[] = '</li>';
            }

        }
        $html[] = '</ul>';

        return implode("\n", $html);
        
    }

}