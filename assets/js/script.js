/*!
 * @package XpertScroller
 * @version ##VERSION##
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

jQuery(document).ready(function()
{
    function resizWidth()
    {
        if( jQuery('.xt-pans').length > 0 )
        {
            jQuery('.xt-pans').each(function(i){

                var el      = jQuery(this),
                    width   = el.width();

                el.css('width', width);
                el.find('.xt-pane').css('width', width);

            });
        }
    }

    resizWidth();

    jQuery(window).bind("resize", function() {
      resizWidth();
    });
});