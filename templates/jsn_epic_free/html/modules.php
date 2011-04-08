<?php
/**
 * @version $Id: modules.php 5556 2006-10-23 19:56:02Z Jinx $
 * @package Joomla
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the sliders style, you would use the following include:
 * <jdoc:include type="module" name="test" style="slider" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */


 /*
 * Module chrome that create 6 div tags to make rounded corner module box.
 */

$template_name = explode( DS, str_replace( array( '\html', '/html' ), '', dirname(__FILE__) ) );
$template_name = $template_name[ count( $template_name ) - 1 ];
require_once (JPATH_THEMES.DS.$template_name.DS.'php'.DS.'lib'.DS.'jsn_utils.php');

function modChrome_jsntrio($module, &$params, &$attribs) { 
	$jsnutils = &JSNUtils::getInstance();
?>
	<div class="<?php echo $params->get('moduleclass_sfx'); ?> jsn-modulecontainer">
    	<div class="jsn-modulecontainer_inner">
            <div class="jsn-top"><div class="jsn-top_inner"></div></div>
            <div class="jsn-middle">
                <div class="jsn-middle_inner">
                    <?php if ($module->showtitle != 0) : ?>
                        <h3 class="jsn-moduletitle"><span class="jsn-moduleicon"><?php echo $jsnutils->wrapFirstWord( $module->title ); ?></span></h3>
                    <?php endif; ?>
                    <div class="jsn-modulecontent">
						<?php echo $module->content; ?>
                		<div class="clearbreak"></div>
                    </div>
                </div>
            </div>
            <div class="jsn-bottom"><div class="jsn-bottom_inner"></div></div>
		</div>
    </div>
<?php } ?>
<?php function modChrome_jsnxhtml($module, &$params, &$attribs) { 
	$jsnutils = &JSNUtils::getInstance();
?>
	<div class="<?php echo $params->get('moduleclass_sfx'); ?> jsn-modulecontainer">
    	<div class="jsn-modulecontainer_inner">
			<?php if ($module->showtitle != 0) : ?>
                <h3 class="jsn-moduletitle"><span class="jsn-moduleicon"><?php echo $jsnutils->wrapFirstWord( $module->title ); ?></span></h3>
            <?php endif; ?>
            <div class="jsn-modulecontent">
				<?php echo $module->content; ?>
                <div class="clearbreak"></div>
			</div>
		</div>
    </div>
<?php } ?>
<?php function modChrome_jsnrounded($module, &$params, &$attribs) { 
	$jsnutils = &JSNUtils::getInstance();
?>
	<div class="<?php echo $params->get('moduleclass_sfx'); ?> jsn-modulecontainer">
		<div>
			<div>
				<div>
					<?php if ($module->showtitle != 0) : ?>
						<h3 class="jsn-moduletitle"><span class="jsn-moduleicon"><?php echo $jsnutils->wrapFirstWord( $module->title ); ?></span></h3>
					<?php endif; ?>
					<div class="jsn-modulecontent">
						<?php echo $module->content; ?>
                        <div class="clearbreak"></div>
                    </div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>