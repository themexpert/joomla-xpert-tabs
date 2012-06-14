<?php
/**
 * @package Xpert Tabs
 * @version 2.1
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

$count = count($list);

$tabs = $params->get('count',5);
$width = ($params->get('width') == NULL OR $params->get('width') == 0 OR $params->get('width') == 'auto')? 'auto': $params->get('width').'px';
$tabs_position = $params->get('tabs_position','top');

if(intval($tabs) > $count) $tabs = $count;
elseif(intval($tabs) == 0) $tabs = $count;

$tabs_title = modXpertTabsHelper::generateTabs($tabs,$list,$params);
?>
    <!--Xpert Tabs by ThemeXpert(www.themexpert.com)- Start-->
    <div id="<?php echo $module_id;?>" class="xt-wrapper <?php echo $params->get('style','style1');?>" style="width:<?php echo $width;?>">
        <?php if($tabs_position == 'top') echo $tabs_title;?>

        <div id="<?php echo $module_id;?>-pans" class="xt-pans">
            <?php
                if ($tabs == 0) $tabs = count($list);
                for($i=0; $i<$tabs; $i++){
                    if($list[$i]->introtext != NULL){
                        echo "<div class='xt-pane'>\n";
                            echo $list[$i]->introtext;
                        echo "</div>\n";
                    }
                }

                ?>
        </div>

        <?php if($tabs_position == 'bottom') echo $tabs_title;?>


    </div>
    <!--Xpert Tabs by ThemeXpert- End-->
