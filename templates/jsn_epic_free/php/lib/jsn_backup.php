<?php
/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( '_JEXEC' ) or die( 'Restricted index access' );
class JSNBackup{
	function JSNBackup(){}
	function &getInstance()
	{
		static $instance;
		if ($instance == null) {
			$instance = new JSNBackup();
		}
		return $instance;
	}	
	function executeBackup($filename, $data)
	{
		$db 				= JFactory::getDBO();
		$config 			= new JConfig();
		$database_prefix	= $config->dbprefix;
		$database_name 		= $config->db;
		$back_up_file 		= 'jsn_'.$filename.'_backup_'.date('YmdHis').'.sql';
		$file_name_zip 		= 'jsn_'.$filename.'_backup_'.date('YmdHis').'.zip';
		$folder_temp  		= JPATH_ROOT.DS.'tmp';
		$back_up_file 		= $folder_temp.DS.$back_up_file;
		$file_name_zip 		= $folder_temp.DS.$file_name_zip;
 		$schema = '# JSN Template Solution' . "\n" .
                  '# http://joomlashine.com/' . "\n" .
                  '#' . "\n" .                 
                  '# Copyright (c) ' . date('Y') . ' JoomlaShine.com. All rights reserved.' . "\n" .
                  '#' . "\n" .
                  '# Database: ' . $database_name . "\n" .
                  '# Database Server: localhost' . "\n" .
                  '#' . "\n" .
                  '# Backup Date: ' . date("F j, Y, g:i a") . "\n\n";         
		if($data != false && is_array($data))
		{
	        foreach ($data as $value)
			{
				$tables = $value->backup;
				if(count($tables))
				{
					foreach ($tables as $table)
					{
						 $schema .= $this->getTableInfo($db, $table, $database_prefix);
					}
				}
			}
		}
		jimport('joomla.filesystem.file');
		JFile::write($back_up_file, $schema);
		$this->createZip($file_name_zip, $back_up_file);
		JFile::delete($back_up_file);
		return JFile::getName($file_name_zip);
	}
	
	function getTableInfo($database_object, $table_name, $database_prefix)
	{
        $table_info =  $database_object->getTableCreate('#__'.$table_name);
        $schema 	=  "\n".'DROP TABLE IF EXISTS `'.$database_prefix.$table_name.'`;' . "\n";
        $schema 	.= $table_info['#__'.$table_name].';';
        $schema 	.= "\n\n";
        $table_fields = $database_object->getTableFields('#__'.$table_name, false);
		foreach ($table_fields as $key=>$table_field)
		{				
			foreach ($table_field as $value)
			{
				$field_list[] = $value->Field;
			}							
		}
		$query 		= 'SELECT `' . implode('`, `', $field_list) . '` FROM #__'.$table_name;
		$database_object->setQuery($query);
		$rows_query = $database_object->loadAssocList();
		if(count($rows_query)){
			foreach ($rows_query as $value)
			{
				$schema .= 'INSERT INTO `'.$database_prefix.$table_name.'` (`' . implode('`, `', $field_list) . '`) VALUES (';
				reset($field_list);
				foreach ($field_list as $field_value)
				{
					if(!isset($value[$field_value])){
						 $schema .= 'NULL, ';
					}elseif (!empty($value[$field_value]) && !is_null($value[$field_value])){
						$row 	= addslashes($value[$field_value]);
						$row 	= ereg_replace("\n#", "\n".'\#', $row);
						$schema .= '\'' . $row . '\', ';
					}else{
						$schema .= '\'\', ';
					}
				}
				$schema = ereg_replace(', $', '', $schema) . ');' . "\n";			
			}
		}		
		return 	$schema;			
	}
	function createZip($file_name_zip, $file)
	{
  		jimport('joomla.filesystem.file');
    	jimport('joomla.filesystem.archive');	
		$zip_adapter =& JArchive::getAdapter('zip'); //Compression type		
        $data = JFile::read($file);          
        $files_array[] = array('name' => JFile::getName($file), 'data' => $data);        
        $zip_adapter->create( $file_name_zip, $files_array, 'zip');     			
	}
	function downloadFile($type, $file_name)
	{
		jimport('joomla.filesystem.file');
		$folder_temp	= JPATH_ROOT.DS.'tmp';
		$file_path 		= $folder_temp.DS.$file_name;
		$file_size 		= filesize($file_path);
		switch ($type)
		{
			case "zip":
				header("Content-Type: application/zip");
				break;
			case "bzip":
				header("Content-Type: application/x-bzip2");
				break;
			case "gzip":
				header("Content-Type: application/x-gzip");
				break;
			case "tar":
				header("Content-Type: application/x-tar");
		}
		$header = "Content-Disposition: attachment; filename=\"";
		$header .= $file_name;
		$header .= "\"";
		header($header);
		header('Content-Description: File Transfer');
		header("Content-Length: " . $file_size);
		header("Content-Transfer-Encoding: binary");
		header("Cache-Control: no-cache, must-revalidate, max-age=60");
		header("Expires: Sat, 01 Jan 2000 12:00:00 GMT");
		ob_clean();
   	 	flush();
		@readfile($file_path);
		//JFile::delete($file_path);		
	}	
}
?>