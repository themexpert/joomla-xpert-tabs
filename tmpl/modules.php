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
<div id="<?php echo $module_id;?>" class="txtabs-content <?php echo $params->get('style','style1');?>">

    <?php if($tabs_position == 'top') echo $tabs_title;?>

    <div class="txtabs-content">
    <?php for($i=0; $i<$tabs; $i++): ?>
        <?php if($items[$i]->content != NULL) :?>

            <?php $class = ($i == 0) ? ' active in' : '';?>

            <div class="txtabs-pane<?php echo $class; ?> <?php echo $transition; ?> clearfix" id="txtabs-<?php echo $i; ?>">
                <div class="txtabs-pane-in">
                    <?php echo $items[$i]->content; ?>
                </div>
            </div>
        <?php endif;?>
    <?php endfor; ?>
    </div>

    <?php if($tabs_position == 'bottom') echo $tabs_title;?>

</div>
<!--Xpert Tabs by ThemeXpert- End-->