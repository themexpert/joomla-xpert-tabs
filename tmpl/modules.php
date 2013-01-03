<?php
/**
 * @package Xpert Tabs
 * @version 2.1
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

?>
<!--Xpert Tabs by ThemeXpert(www.themexpert.com)- Start-->
    <div id="<?php echo $module_id;?>" class="xt-wrapper <?php echo $params->get('style','style1');?>">

        <?php if($tabs_position == 'top') echo $tabs_title;?>

        <a class="xt-next" style="display:none;"></a>
        <a class="xt-prev" style="display:none;"></a>

        <div id="<?php echo $module_id;?>"  class="xt-pans" style="height:<?php echo $height;?>">

            <div class="xt-items">
                <?php
                    if ($tabs == 0) $tabs = count($items);
                    for($i=0; $i<$tabs; $i++){
                        if($items[$i]->content != NULL){
                            echo "<div class='xt-pane'>\n";
                                echo "<div class='xt-pane-in'>\n";
                                    echo $items[$i]->content;
                                echo "</div>\n";
                            echo "</div>\n";
                        }
                    }

                    ?>
            </div>
        </div>

        <?php if($tabs_position == 'bottom') echo $tabs_title;?>

    </div>
<!--Xpert Tabs by ThemeXpert- End-->