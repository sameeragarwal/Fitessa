<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
$edit 	= JRequest::getVar('edit',true);
$editor =& JFactory::getEditor();
$cid 	= JRequest::getVar( 'cid', array(0), 'get', 'array' );
JToolBarHelper::title(JText::_('JSN IMAGESHOW').': '.JText::_('SHOWCASE SETTINGS'), 'showcase');
JToolBarHelper::save();
JToolBarHelper::apply();
if (!$edit)  
{
	JToolBarHelper::cancel();
} 
else 
{
	JToolBarHelper::cancel('cancel', 'Close');
}
JToolBarHelper::divider();
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
jimport('joomla.html.pane');
$myTabs 	= & JPane::getInstance('tabs',array('startOffset'=>0));
$showCaseID = (int) $this->items->showcase_id;
?>
<script language="javascript" type="text/javascript">
	window.addEvent('domready', function()	{
		JSNISImageShow.ShowcaseChangeBg();
	});
	
	var original_value = '';
	function submitbutton(pressbutton) 
	{
		var form = document.adminForm;

		if (pressbutton == 'cancel')
		{
			submitform( pressbutton );
			return;
		}
		
		if (form.showcase_title.value == "")
		{
			alert( "<?php echo JText::_('REQUIRED FIELD TITLE CANNOT BE LEFT BLANK', true); ?>");
		}
		
		else if(form.theme_name == undefined)
		{
			alert( "<?php echo JText::_('SELECT A SHOWCASE THEME', true); ?>");
		}
		else
		{
			submitform( pressbutton );
		}
	}
	
	function getInputValue(object)
	{
		original_value = object.value;
	}
	
	function checkInputValue(object, percent)
	{
		var patt;
		var form 		= document.adminForm;
		var msg;
		if(percent == 1)
		{
			patt=/^[0-9]+(\%)?$/;
			msg = "<?php echo JText::_('ALLOW ONLY DIGITS AND THE % CHARACTER', true); ?>";
		}
		else
		{
			patt=/^[0-9]+$/;
			msg = "<?php echo JText::_('ALLOW ONLY DIGITS', true); ?>";
		}
		if(!patt.test(object.value))
		{
			alert (msg);
			object.value = original_value;
			return;
		}
	}	
</script>
<!--[if IE 7]>
	<link href="<?php echo JURI::base();?>components/com_imageshow/assets/css/fixie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<form action="index.php?option=com_imageshow&controller=showcase" method="POST" name="adminForm" id="adminForm">
	<?php
	$uri	        =& JURI::getInstance();
	$base['prefix'] = $uri->toString( array('scheme', 'host', 'port'));
	$base['path']   =  rtrim(dirname(str_replace(array('"', '<', '>', "'",'administrator'), '', $_SERVER["PHP_SELF"])), '/\\');
	$url 			= $base['prefix'].$base['path'].'/';
?>
	<div class="jsnis-dgrey-heading jsnis-dgrey-heading-style">
		<h3 class="jsnis-element-heading"><?php echo JText::_('TITLE SHOWCASE DETAILS'); ?></h3>
	</div>
	<table class="jsnis-showcase-settings" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top" style="width: 50%;"><fieldset>
					<legend><?php echo JText::_('GENERAL');?></legend>
					<table class="admintable showcase-details">
						<?php
							if($showCaseID != 0){			
						?>
						<tr>
							<td class="key"><?php echo JText::_('ID');?></td>
							<td><?php echo $showCaseID; ?></td>
						</tr>
						<?php
							}
						?>
						<tr>
							<td class="key"><?php echo JText::_('TITLE');?></td>
							<td><input type="text" style="width: 90%;" name="showcase_title" id="showcase_title" value="<?php echo $this->generalData['generalTitle']; ?>" />
								<font color="Red"> *</font></td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('PUBLISHED');?></td>
							<td><?php echo $this->lists['published']; ?></td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('ORDER');?></td>
							<td><?php echo $this->lists['ordering']; ?></td>
						</tr>
					</table>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('APPEARANCE'); ?></legend>
					<table class="admintable" width="100%" >
						<tbody>
							<tr>
								<td class="key"><?php echo JText::_('OVERALL WIDTH'); ?></td>
								<td><input type="text" size="5" name="general_overall_width" value="<?php echo $this->generalData['generalWidth']; ?>" onchange="checkInputValue(this, 1);" onfocus="getInputValue(this);" /></td>
							</tr>
							<tr>
								<td class="key"><?php echo JText::_('OVERALL HEIGHT'); ?></td>
								<td><input type="text" size="5" name="general_overall_height" value="<?php echo $this->generalData['generalHeight']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE ROUND CORNER');?>::<?php echo JText::_('DES ROUND CORNER'); ?>"><?php echo JText::_('TITLE ROUND CORNER'); ?></span></td>
								<td><input type="text" size="5" name="general_round_corner_radius" value="<?php echo $this->generalData['generalCornerRadius']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE BORDER STOKE');?>::<?php echo JText::_('DES BORDER STOKE'); ?>"><?php echo JText::_('TITLE BORDER STOKE'); ?></span></td>
								<td><input type="text" size="5" name="general_border_stroke" value="<?php echo $this->generalData['generalBorderStroke']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE OUTSITE BACKGROUND COLOR');?>::<?php echo JText::_('DES OUTSITE BACKGROUND COLOR'); ?>"><?php echo JText::_('TITLE OUTSITE BACKGROUND COLOR');?></span></td>
								<td class="showcase-input-field"><input type="text" size="10" readonly="readonly" name="background_color" id="background_color" value="<?php echo $this->generalData['generalBgColor']; ?>" />
									<a href="" id="general_background_color"><span id="span_background_color" class="jsnis-icon-view-color" style="background:<?php echo $this->generalData['generalBgColor']; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE BORDER COLOR');?>::<?php echo JText::_('DES BORDER COLOR'); ?>"><?php echo JText::_('TITLE BORDER COLOR'); ?></span></td>
								<td class="showcase-input-field"><input type="text" size="10" id="general_border_color" readonly="readonly" name="general_border_color" value="<?php echo $this->generalData['generalBorderColor']; ?>" />
									<a href="" id="link_general_border_color"><span id="span_general_border_color" class="jsnis-icon-view-color" style="background:<?php echo $this->generalData['generalBorderColor']; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a></td>
							</tr>
						</tbody>
					</table>
				</fieldset></td>
			<td valign="top"><fieldset>
					<legend><?php echo JText::_('IMAGES LOADING'); ?></legend>
					<table class="admintable" width="100%">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMAGES PRELOADING NUMBER');?>::<?php echo JText::_('DES IMAGES PRELOADING NUMBER'); ?>"><?php echo JText::_('TITLE IMAGES PRELOADING NUMBER'); ?></span></td>
								<td><input type="text" size="5" name="general_number_images_preload" value="<?php echo $this->generalData['generalImageLoad']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE GENERAL IMAGES LOADING ORDER');?>::<?php echo JText::_('DES GENERAL IMAGES LOADING ORDER'); ?>"><?php echo JText::_('TITLE GENERAL IMAGES LOADING ORDER'); ?></span></td>
								<td><?php echo $this->lists['generalImagesOrder'];?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('IMAGE DETAILS'); ?></legend>
					<table class="admintable" width="100%">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE TITLE SOURCE');?>::<?php echo JText::_('DES TITLE SOURCE'); ?>"><?php echo JText::_('TITLE TITLE SOURCE'); ?></span></td>
								<td><?php echo $this->lists['generalTitleSource']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE DESCRIPTION SOURCE');?>::<?php echo JText::_('DES DESCRIPTION SOURCE'); ?>"><?php echo JText::_('TITLE DESCRIPTION SOURCE'); ?></span></td>
								<td><?php echo $this->lists['generalDesSource']; ?></td>
							</tr>
							<tr>
								<td class="key" nowrap><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE LINK SOURCE');?>::<?php echo JText::_('DES LINK SOURCE'); ?>"><?php echo JText::_('TITLE LINK SOURCE'); ?></span></td>
								<td><?php echo $this->lists['generalLinkSource']; ?></td>
							</tr>
							<tr>
								<td class="key" nowrap><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE OPEN LINK IN');?>::<?php echo JText::_('DES OPEN LINK IN'); ?>"><?php echo JText::_('TITLE OPEN LINK IN'); ?></span></td>
								<td><?php echo $this->lists['generalOpenLinkIn']; ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset></td>
		</tr>
	</table>
	<div id="jsn-showcase-theme-wrapper">
		<?php
		$objShowcaseTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$objShowcase		= JSNISFactory::getObj('classes.jsn_is_showcase');
		$themes 			= $objShowcaseTheme->listThemes(false);
		$countTheme 		= count($themes);
		$theme				= JRequest::getVar('theme');
		
		if ($this->items->theme_id && $objShowcaseTheme->checkThemeExist($this->items->theme_name))
		{
			$objShowcaseTheme->loadThemeByName($this->items->theme_name);
		}	
		else
		{
			if ($countTheme == 1)
			{
				$objShowcaseTheme->loadThemeByName(@$themes[0]['element']);
			}
			elseif ($countTheme && $theme != '')
			{
				$objShowcaseTheme->loadThemeByName($theme);
			}		
			else
			{
				echo $this->loadTemplate('themes'); 
			}
		}
	?>
	</div>
	<input type="hidden" name="redirectLinkTheme" value="" />
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="showcase" />
	<input type="hidden" name="cid[]" value="<?php echo (int) $this->items->showcase_id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>
