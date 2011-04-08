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
$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN IMAGESHOW').': '.JText::_('SHOWLIST SETTINGS'), 'showlist-settings' );
JToolBarHelper::save('save');
JToolBarHelper::apply('apply');
JToolBarHelper::cancel('cancel','close');
JToolBarHelper::divider();
$objJSNUtils->callJSNButtonMenu();
$showListID = (int) $this->items->showlist_id;
$task = JRequest::getVar('task');

if ($task == 'edit')
{
	echo "<div id=\"jsn-showlist-toolbar-css\"><style>#toolbar-save,#toolbar-apply{display:none;}</style></div>";
}

?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton)
	{
		var form 		= document.adminForm;
		var link  		= form.showlist_link.value;
		var flexElement = document.getElementById('flash');
		var task 		= '<?php echo $task; ?>';

		if (pressbutton == 'cancel')
		{
			submitform( pressbutton );
			return;
		}

		if (form.showlist_title.value == "")
		{
			alert( "<?php echo JText::_('SHOWLIST MUST HAVE A TITLE', true); ?>");
			return;
		}
		else if(form.showlist_link.value != "" && !link.match(new RegExp(/^http:\/\/[www\.]?[a-zA-Z-0-9]+\.[a-zA-Z]/i)))
		{
			alert("<?php echo JText::_('SHOWLIST LINK FORMAT INCORRECT', true); ?>");
			return;
		}
		else
		{
			if(task != 'add')
			{
				try
				{
					flexElement.saveFlex(pressbutton);
				}
				catch(e){}
			}
			else
			{
				submitform( pressbutton );
			}
		}
	}
</script>

<form name='adminForm' action="index.php?option=com_imageshow&controller=showlist" method="post" />

	<div class="jsnis-dgrey-heading jsnis-dgrey-heading-style">
		<h3 class="jsnis-element-heading"><?php echo JText::_('TITLE SHOWLIST DETAILS');?></h3>
	</div>
	<table class="jsnis-showlist-settings" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top" style="width: 50%;"><fieldset>
					<legend> <?php echo JText::_('GENERAL');?> </legend>
					<table class="admintable" border="0" style="width:100%;">
						<tbody>
							<?php
					if($showListID != 0){
				?>
							<tr>
								<td class="key"><?php echo JText::_('ID');?></td>
								<td><?php echo $showListID; ?></td>
							</tr>
							<?php
					}
				?>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOWLIST');?>::<?php echo JText::_('DES SHOWLIST'); ?>"><?php echo JText::_('TITLE SHOWLIST');?></span></td>
								<td><input style="width:96%;" type="text" value="<?php echo htmlspecialchars($this->items->showlist_title);?>" name="showlist_title"/>
									<font color="Red"> *</font></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE PUBLISHED');?>::<?php echo JText::_('DES PUBLISHED'); ?>"><?php echo JText::_('PUBLISHED');?></span></td>
								<td><?php echo $this->lists['published']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE ORDER');?>::<?php echo JText::_('DES ORDER'); ?>"><?php echo JText::_('TITLE ORDER');?></span></td>
								<td><?php echo $this->lists['ordering']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE HITS');?>::<?php echo JText::_('DES HITS'); ?>"><?php echo JText::_('HITS');?></span></td>
								<td><input size="15" type="text" name="hits" value="<?php echo ($this->items->hits!='')?$this->items->hits:0;?>" /></td>
							</tr>
							<tr>
								<td valign="top" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE DESCRIPTION');?>::<?php echo JText::_('DES DESCRIPTION'); ?>"><?php echo JText::_('TITLE DESCRIPTION');?></span></td>
								<td><textarea style="width:100%; height:100px;" name="description"><?php echo $this->items->description; ?></textarea></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE LINK');?>::<?php echo JText::_('DES LINK'); ?>"><?php echo JText::_('LINK');?></span></td>
								<td><input style="width: 100%;" type="text" name="showlist_link" value="<?php echo htmlspecialchars($objJSNUtils->decodeUrl($this->items->showlist_link)); ?>" /></td>
							</tr>
						</tbody>
					</table>
				</fieldset></td>
			<td valign="top"><fieldset>
					<legend><?php echo JText::_('ACCESS PERMISSION'); ?></legend>
					<table class="admintable" border="0" style="width: 100%;">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE ACCESS LEVEL');?>::<?php echo JText::_('DES ACCESS LEVEL'); ?>"><?php echo JText::_('TITLE ACCESS LEVEL');?></span></td>
								<td><?php echo $this->lists['access']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE AUTHORIZATION MESSAGE');?>::<?php echo JText::_('DES AUTHORIZATION MESSAGE'); ?>"><?php echo JText::_('TITLE AUTHORIZATION MESSAGE');?></span></td>
								<td class="paramlist_value"><?php echo $this->lists['authorizationCombo']; ?>
									<div style="<?php echo ($this->items->authorization_status == 1)?'display:"";':'display:none;'; ?>" id="wrap-aut-article">
										<input class="showlist-input jsnis-readonly" type="text" id="aid_name" value="<?php echo @$this->items->aut_article_title;?>" readonly="readonly" />
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 651, y: 375}}" href="index.php?option=com_content&task=element&tmpl=component&object=aid" title="Select Content"><?php echo JText::_('SELECT');?></a>
												<input type="hidden" id="aid_id" name="alter_autid" value="<?php echo $this->items->alter_autid;?>" />
											</div>
										</div>
									</div></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('ALTERNATIVE & SEO CONTENT'); ?></legend>
					<table class="admintable" border="0" style="width:100%;">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE ALTERNATIVE CONTENT');?>::<?php echo JText::_('DES ALTERNATIVE CONTENT'); ?>"><?php echo JText::_('TITLE ALTERNATIVE CONTENT');?></span></td>
								<td class="paramlist_value"><?php echo $this->lists['alternativeContentCombo']; ?>
									<div style="<?php echo ($this->items->alternative_status == 2)?'':'display: none;'; ?>" id="wrap-btt-article">
										<input class="showlist-input jsnis-readonly" type="text" id="id_name" value="<?php echo @$this->items->article_title;?>" readonly="readonly"/>
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 651, y: 375}}" href="index.php?option=com_content&task=element&tmpl=component&object=id" title="Select Content"><?php echo JText::_('SELECT');?></a>
												<input type="hidden" id="id_id" name="alter_id" value="<?php echo $this->items->alter_id;?>" />
											</div>
										</div>
									</div>
									<div style="<?php echo ($this->items->alternative_status == 1)?'':'display:none;'; ?>" id="wrap-btt-module">
										<input class="showlist-input jsnis-readonly" type="text" id="alter_module_title" value="<?php echo @$this->items->alter_module_title;?>" readonly="readonly"/>
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 600}}" href="index.php?option=com_imageshow&controller=showlist&task=modules&tmpl=component&object=alter_module" title="Select Modules"><?php echo JText::_('SELECT');?></a>
												<input type="hidden" id="alter_module_id" name="alter_module_id" value="<?php echo $this->items->alter_module_id;?>" />
											</div>
										</div>
									</div>
									<div style="<?php echo ($this->items->alternative_status == 3)?'':'display:none;'; ?>" id="wrap-btt-image">
										<input class="showlist-input jsnis-readonly" type="text" id="alter_image_path" name="alter_image_path" value="<?php echo @$this->items->alter_image_path;?>" readonly="readonly"/>
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 420}}" href="index.php?option=com_imageshow&controller=media&act=showlist&tmpl=component&e_name=text" title="Select Modules"><?php echo JText::_('SELECT');?></a> </div>
										</div>
									</div></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SEO CONTENT');?>::<?php echo JText::_('DES SEO CONTENT'); ?>"><?php echo JText::_('TITLE SEO CONTENT');?></span></td>
								<td class="paramlist_value"><?php echo $this->lists['seoContent']; ?>
									<div style="<?php echo ($this->items->seo_status == 1)?'':'display: none;'; ?>" id="wrap-seo-article">
										<input class="showlist-input jsnis-readonly" type="text" id="seo_article_name" value="<?php echo @$this->items->seo_article_title;?>" readonly="readonly"/>
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 651, y: 375}}" href="index.php?option=com_content&task=element&tmpl=component&object=seo_article" title="Select Content"><?php echo JText::_('SELECT');?></a>
												<input type="hidden" id="seo_article_id" name="seo_article_id" value="<?php echo $this->items->seo_article_id;?>" />
											</div>
										</div>
									</div>
									<div style="<?php echo ($this->items->seo_status == 2)?'':'display:none;'; ?>" id="wrap-seo-module">
										<input class="showlist-input jsnis-readonly" type="text" id="seo_module_title" value="<?php echo @$this->items->seo_module_title;?>" readonly="readonly"/>
										<div class="button2-left" >
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 600}}" href="index.php?option=com_imageshow&controller=showlist&task=modules&tmpl=component&object=seo_module" title="Select Modules"><?php echo JText::_('SELECT');?></a>
												<input type="hidden" id="seo_module_id" name="seo_module_id" value="<?php echo $this->items->seo_module_id;?>" />
											</div>
										</div>
									</div></td>
							</tr>
						</tbody>
					</table>
				</fieldset></td>
	</table>
	<input type="hidden" name="cid[]" value="<?php echo (int) $this->items->showlist_id;?>" />
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="showlist" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
