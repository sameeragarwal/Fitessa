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

class JSNISCompareFiles
{
	function compareFile($file1, $file2)
	{
		$mainFileArray 			= file($file1);
		$duplicateFileArray 	= file($file2);
	 	return $this->compareFileContent($mainFileArray, $duplicateFileArray);
	}

	function compareFileContent($mainFileArray, $duplicateFileArray)
	{
		$strReplace 	  = array('\\', '/');
		$file1ReturnArray = array();
		$file2ReturnArray = array();

     	$linesInMainFile 		= count($mainFileArray);
		$linesInDuplicateFile 	= count($duplicateFileArray);

		if($linesInMainFile == 0 || $linesInDuplicateFile == 0) return $file2ReturnArray;

		if($linesInMainFile >= $linesInDuplicateFile)
		{
			 for ($i = 0; $i < $linesInDuplicateFile; $i++)
			 {
				if(trim($duplicateFileArray[$i]) != '')
				{
			 		$lineInDuplicateFile						= explode("\t", $duplicateFileArray[$i]);
			 		$file2ReturnArray[str_replace($strReplace, DS, @$lineInDuplicateFile[0])]	= @$lineInDuplicateFile[1];
				}
			 }

			 for ($i = 0; $i < $linesInMainFile; $i++)
			 {
			 	if(trim($mainFileArray[$i]) != '')
			 	{
			 		$lineInMainFile			= explode("\t", $mainFileArray[$i]);
					unset($file2ReturnArray[str_replace($strReplace, DS, $lineInMainFile[0])]);
			 	}
			 }
		}
		else
		{
			for ($i = 0; $i < $linesInMainFile; $i++)
			{
				if(trim($mainFileArray[$i]) != '')
			 	{
					$lineInMainFile							= explode("\t", $mainFileArray[$i]);
					$file1ReturnArray[str_replace($strReplace, DS, $lineInMainFile[0])]	= $lineInMainFile[1];
			 	}
			}

			for ($i = 0; $i < $linesInDuplicateFile; $i++)
			{
				if(trim($duplicateFileArray[$i]) != '')
				{
					$lineInDuplicateFile	= explode("\t", $duplicateFileArray[$i]);
					if(!isset($file1ReturnArray[str_replace($strReplace, DS, @$lineInDuplicateFile[0])]))
					{
						$file2ReturnArray[str_replace($strReplace, DS, @$lineInDuplicateFile[0])]	= @$lineInDuplicateFile[1];
					}
				}
			}
		}
		return $file2ReturnArray;
	}
}
//$result = $objCompareFiles->compareFile('jsn_imageshow.2.5.1.checksum', 'jsn_imageshow.2.5.checksum');