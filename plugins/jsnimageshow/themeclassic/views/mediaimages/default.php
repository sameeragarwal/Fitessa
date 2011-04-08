<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access'); 
if (count($this->images) > 0 || count($this->folders) > 0) { ?>
	<script type='text/javascript'>		
		window.addEvent('domready', function()
		{	
			var div_graphic = $$('div.jsn-graphic');
			div_graphic.addEvent('click', function()
			{
				div_graphic.removeClass('jsn-graphic-selected');
				this.addClass('jsn-graphic-selected');
			});
		});
		
		function setBGImageSelected()
		{
			var div = $$('div.jsn-graphic');
			var str = escape(window.parent.$('f_url').value);

			if (str != '')
			{
				div.each(function(element, i)
				{		
					var src = element.getElementsByTagName('IMG')[0].src;

					if(src.indexOf(str) != -1 && str != '')
					{
						element.addClass('jsn-graphic-selected');
					}
				});
			}
		}
		
		setTimeout("setBGImageSelected()", 200);
	</script>
	<div class="manager">
		<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
			$objThemeMedia->setFolder($i, $this);
			include(dirname(__FILE__).DS.'default_folder.php');
		endfor; ?>

		<?php for ($i=0,$n=count($this->images); $i<$n; $i++) :
			$objThemeMedia->setImage($i, $this);
			include(dirname(__FILE__).DS.'default_image.php');
		endfor; ?>
	</div>
<?php } else { ?>
	<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">
					<?php echo JText::_( 'No Images Found' ); ?>
				</div>
			</td>
		</tr>
	</table>
<?php } ?>
