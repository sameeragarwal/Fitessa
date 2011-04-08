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
class JSNISArchive
{
	function JSNISArchive($name)
	{
		$this->options = array (
			'basedir' => ".",
			'name' => $name,
			'prepend' => "",
			'inmemory' => 0,
			'overwrite' => 0,
			'recurse' => 1,
			'storepaths' => 1,
			'followlinks' => 0,
			'level' => 3,
			'method' => 1,
			'sfx' => "",
			'type' => "",
			'comment' => ""
		);
		$this->files = array ();
		$this->exclude = array ();
		$this->storeonly = array ();
		$this->error = array ();
	}

	function &getInstance()
	{
		static $instanceArchive;
		if ($instanceArchive == null)
		{
			$instanceArchive = new JSNISArchive();
		}
		return $instanceArchive;
	}		
	
	function setOptions($options)
	{
		foreach ($options as $key => $value)
			$this->options[$key] = $value;
		if (!empty ($this->options['basedir']))
		{
			$this->options['basedir'] = str_replace("\\", "/", $this->options['basedir']);
			$this->options['basedir'] = preg_replace("/\/+/", "/", $this->options['basedir']);
			$this->options['basedir'] = preg_replace("/\/$/", "", $this->options['basedir']);
		}
		if (!empty ($this->options['name']))
		{
			$this->options['name'] = str_replace("\\", "/", $this->options['name']);
			$this->options['name'] = preg_replace("/\/+/", "/", $this->options['name']);
		}
		if (!empty ($this->options['prepend']))
		{
			$this->options['prepend'] = str_replace("\\", "/", $this->options['prepend']);
			$this->options['prepend'] = preg_replace("/^(\.*\/+)+/", "", $this->options['prepend']);
			$this->options['prepend'] = preg_replace("/\/+/", "/", $this->options['prepend']);
			$this->options['prepend'] = preg_replace("/\/$/", "", $this->options['prepend']) . "/";
		}
	}

	function createArchive()
	{
		$this->makeList();
		
		if ($this->options['inmemory'] == 0)
		{
			$pwd = getcwd();
			chdir($this->options['basedir']);
			if ($this->options['overwrite'] == 0 && file_exists($this->options['name'] . ($this->options['type'] == "gzip" || $this->options['type'] == "bzip" ? ".tmp" : "")))
			{
				$this->error[] = "File {$this->options['name']} already exists.";
				chdir($pwd);
				return 0;
			}
			else if ($this->JSNISArchive = @fopen($this->options['name'] . ($this->options['type'] == "gzip" || $this->options['type'] == "bzip" ? ".tmp" : ""), "wb+"))
				chdir($pwd);
			else
			{
				$this->error[] = "Could not open {$this->options['name']} for writing.";
				chdir($pwd);
				return 0;
			}
		}
		else
			$this->JSNISArchive = "";

		switch ($this->options['type'])
		{
		case "zip":
			if (!$this->createZip())
			{
				$this->error[] = "Could not create zip file.";
				return 0;
			}
			break;
		case "bzip":
			if (!$this->createTar())
			{
				$this->error[] = "Could not create tar file.";
				return 0;
			}
			if (!$this->createBzip())
			{
				$this->error[] = "Could not create bzip2 file.";
				return 0;
			}
			break;
		case "gzip":
			if (!$this->createTar())
			{
				$this->error[] = "Could not create tar file.";
				return 0;
			}
			if (!$this->create_gzip())
			{
				$this->error[] = "Could not create gzip file.";
				return 0;
			}
			break;
		case "tar":
			if (!$this->createTar())
			{
				$this->error[] = "Could not create tar file.";
				return 0;
			}
		}

		if ($this->options['inmemory'] == 0)
		{
			fclose($this->JSNISArchive);
			if ($this->options['type'] == "gzip" || $this->options['type'] == "bzip")
				unlink($this->options['basedir'] . "/" . $this->options['name'] . ".tmp");
		}
		
	}

	function addData($data)
	{
		if ($this->options['inmemory'] == 0)
			fwrite($this->JSNISArchive, $data);
		else
			$this->JSNISArchive .= $data;
	}

	function makeList()
	{
		if (!empty ($this->exclude))
			foreach ($this->files as $key => $value)
				foreach ($this->exclude as $current)
					if ($value['name'] == $current['name'])
						unset ($this->files[$key]);
		if (!empty ($this->storeonly))
			foreach ($this->files as $key => $value)
				foreach ($this->storeonly as $current)
					if ($value['name'] == $current['name'])
						$this->files[$key]['method'] = 0;
		unset ($this->exclude, $this->storeonly);
	}

	function addFiles($list)
	{
		$temp = $this->listFiles($list);
		foreach ($temp as $current)
			$this->files[] = $current;
	}

	function excludeFiles($list)
	{
		$temp = $this->listFiles($list);
		foreach ($temp as $current)
			$this->exclude[] = $current;
	}

	function storeFiles($list)
	{
		$temp = $this->listFiles($list);
		foreach ($temp as $current)
			$this->storeonly[] = $current;
	}

	function listFiles($list)
	{
		if (!is_array ($list))
		{
			$temp = $list;
			$list = array ($temp);
			unset ($temp);
		}

		$files = array ();

		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($list as $current)
		{
			$current = str_replace("\\", "/", $current);
			$current = preg_replace("/\/+/", "/", $current);
			$current = preg_replace("/\/$/", "", $current);
			if (strstr($current, "*"))
			{
				$regex = preg_replace("/([\\\^\$\.\[\]\|\(\)\?\+\{\}\/])/", "\\\\\\1", $current);
				$regex = str_replace("*", ".*", $regex);
				$dir = strstr($current, "/") ? substr($current, 0, strrpos($current, "/")) : ".";
				$temp = $this->parseDir($dir);
				foreach ($temp as $current2)
					if (preg_match("/^{$regex}$/i", $current2['name']))
						$files[] = $current2;
				unset ($regex, $dir, $temp, $current);
			}
			else if (@is_dir($current))
			{
				$temp = $this->parseDir($current);
				foreach ($temp as $file)
					$files[] = $file;
				unset ($temp, $file);
			}
			else if (@file_exists($current))
				$files[] = array ('name' => $current, 'name2' => $this->options['prepend'] .
					preg_replace("/(\.+\/+)+/", "", ($this->options['storepaths'] == 0 && strstr($current, "/")) ?
					substr($current, strrpos($current, "/") + 1) : $current),
					'type' => @is_link($current) && $this->options['followlinks'] == 0 ? 2 : 0,
					'ext' => substr($current, strrpos($current, ".")), 'stat' => stat($current));
		}

		chdir($pwd);

		unset ($current, $pwd);

		usort($files, array ("JSNISArchive", "sortFiles"));

		return $files;
	}

	function parseDir($dirname)
	{
		if ($this->options['storepaths'] == 1 && !preg_match("/^(\.+\/*)+$/", $dirname))
			$files = array (array ('name' => $dirname, 'name2' => $this->options['prepend'] .
				preg_replace("/(\.+\/+)+/", "", ($this->options['storepaths'] == 0 && strstr($dirname, "/")) ?
				substr($dirname, strrpos($dirname, "/") + 1) : $dirname), 'type' => 5, 'stat' => stat($dirname)));
		else
			$files = array ();
		$dir = @opendir($dirname);

		while ($file = @readdir($dir))
		{
			$fullname = $dirname . "/" . $file;
			if ($file == "." || $file == "..")
				continue;
			else if (@is_dir($fullname))
			{
				if (empty ($this->options['recurse']))
					continue;
				$temp = $this->parseDir($fullname);
				foreach ($temp as $file2)
					$files[] = $file2;
			}
			else if (@file_exists($fullname))
				$files[] = array ('name' => $fullname, 'name2' => $this->options['prepend'] .
					preg_replace("/(\.+\/+)+/", "", ($this->options['storepaths'] == 0 && strstr($fullname, "/")) ?
					substr($fullname, strrpos($fullname, "/") + 1) : $fullname),
					'type' => @is_link($fullname) && $this->options['followlinks'] == 0 ? 2 : 0,
					'ext' => substr($file, strrpos($file, ".")), 'stat' => stat($fullname));
		}

		@closedir($dir);

		return $files;
	}

	function sortFiles($a, $b)
	{
		if ($a['type'] != $b['type'])
			if ($a['type'] == 5 || $b['type'] == 2)
				return -1;
			else if ($a['type'] == 2 || $b['type'] == 5)
				return 1;
		else if ($a['type'] == 5)
			return strcmp(strtolower($a['name']), strtolower($b['name']));
		else if ($a['ext'] != $b['ext'])
			return strcmp($a['ext'], $b['ext']);
		else if ($a['stat'][7] != $b['stat'][7])
			return $a['stat'][7] > $b['stat'][7] ? -1 : 1;
		else
			return strcmp(strtolower($a['name']), strtolower($b['name']));
		return 0;
	}

	function downloadFile()
	{
		if ($this->options['inmemory'] == 0)
		{
			$this->error[] = "Can only use downloadFile() if JSNISArchive is in memory. Redirect to file otherwise, it is faster.";
			return;
		}
		
		switch ($this->options['type'])
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
		$header .= strstr($this->options['name'], "/") ? substr($this->options['name'], strrpos($this->options['name'], "/") + 1) : $this->options['name'];
		$header .= "\"";
		header($header);
		header("Content-Length: " . strlen($this->JSNISArchive));
		header("Content-Transfer-Encoding: binary");
		header("Cache-Control: no-cache, must-revalidate, max-age=60");
		header("Expires: Sat, 01 Jan 2000 12:00:00 GMT");
		print($this->JSNISArchive);
	}
}

class JSNISTarFile extends JSNISArchive
{
	function JSNISTarFile($name)
	{
		$this->JSNISArchive($name);
		$this->options['type'] = "tar";
	}

	function createTar()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($this->files as $current)
		{
			if ($current['name'] == $this->options['name'])
				continue;
			if (strlen($current['name2']) > 99)
			{
				$path = substr($current['name2'], 0, strpos($current['name2'], "/", strlen($current['name2']) - 100) + 1);
				$current['name2'] = substr($current['name2'], strlen($path));
				if (strlen($path) > 154 || strlen($current['name2']) > 99)
				{
					$this->error[] = "Could not add {$path}{$current['name2']} to JSNISArchive because the filename is too long.";
					continue;
				}
			}
			$block = pack("a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12", $current['name2'], sprintf("%07o", 
				$current['stat'][2]), sprintf("%07o", $current['stat'][4]), sprintf("%07o", $current['stat'][5]), 
				sprintf("%011o", $current['type'] == 2 ? 0 : $current['stat'][7]), sprintf("%011o", $current['stat'][9]), 
				"        ", $current['type'], $current['type'] == 2 ? @readlink($current['name']) : "", "ustar ", " ", 
				"Unknown", "Unknown", "", "", !empty ($path) ? $path : "", "");

			$checksum = 0;
			for ($i = 0; $i < 512; $i++)
				$checksum += ord(substr($block, $i, 1));
			$checksum = pack("a8", sprintf("%07o", $checksum));
			$block = substr_replace($block, $checksum, 148, 8);

			if ($current['type'] == 2 || $current['stat'][7] == 0)
				$this->addData($block);
			else if ($fp = @fopen($current['name'], "rb"))
			{
				$this->addData($block);
				while ($temp = fread($fp, 1048576))
					$this->addData($temp);
				if ($current['stat'][7] % 512 > 0)
				{
					$temp = "";
					for ($i = 0; $i < 512 - $current['stat'][7] % 512; $i++)
						$temp .= "\0";
					$this->addData($temp);
				}
				fclose($fp);
			}
			else
				$this->error[] = "Could not open file {$current['name']} for reading. It was not added.";
		}

		$this->addData(pack("a1024", ""));

		chdir($pwd);

		return 1;
	}

	function extractFiles()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		if ($fp = $this->open_JSNISArchive())
		{
			if ($this->options['inmemory'] == 1)
				$this->files = array ();

			while ($block = fread($fp, 512))
			{
				$temp = unpack("a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100symlink/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a155prefix/a12temp", $block);
				$file = array (
					'name' => $temp['prefix'] . $temp['name'],
					'stat' => array (
						2 => $temp['mode'],
						4 => octdec($temp['uid']),
						5 => octdec($temp['gid']),
						7 => octdec($temp['size']),
						9 => octdec($temp['mtime']),
					),
					'checksum' => octdec($temp['checksum']),
					'type' => $temp['type'],
					'magic' => $temp['magic'],
				);
				if ($file['checksum'] == 0x00000000)
					break;
				else if (substr($file['magic'], 0, 5) != "ustar")
				{
					$this->error[] = "This script does not support extracting this type of tar file.";
					break;
				}
				$block = substr_replace($block, "        ", 148, 8);
				$checksum = 0;
				for ($i = 0; $i < 512; $i++)
					$checksum += ord(substr($block, $i, 1));
				if ($file['checksum'] != $checksum)
					$this->error[] = "Could not extract from {$this->options['name']}, it is corrupt.";

				if ($this->options['inmemory'] == 1)
				{
					$file['data'] = fread($fp, $file['stat'][7]);
					fread($fp, (512 - $file['stat'][7] % 512) == 512 ? 0 : (512 - $file['stat'][7] % 512));
					unset ($file['checksum'], $file['magic']);
					$this->files[] = $file;
				}
				else if ($file['type'] == 5)
				{
					if (!is_dir($file['name']))
						mkdir($file['name'], $file['stat'][2]);
				}
				else if ($this->options['overwrite'] == 0 && file_exists($file['name']))
				{
					$this->error[] = "{$file['name']} already exists.";
					continue;
				}
				else if ($file['type'] == 2)
				{
					symlink($temp['symlink'], $file['name']);
					chmod($file['name'], $file['stat'][2]);
				}
				else if ($new = @fopen($file['name'], "wb"))
				{
					fwrite($new, fread($fp, $file['stat'][7]));
					fread($fp, (512 - $file['stat'][7] % 512) == 512 ? 0 : (512 - $file['stat'][7] % 512));
					fclose($new);
					chmod($file['name'], $file['stat'][2]);
				}
				else
				{
					$this->error[] = "Could not open {$file['name']} for writing.";
					continue;
				}
				chown($file['name'], $file['stat'][4]);
				chgrp($file['name'], $file['stat'][5]);
				touch($file['name'], $file['stat'][9]);
				unset ($file);
			}
		}
		else
			$this->error[] = "Could not open file {$this->options['name']}";

		chdir($pwd);
	}

	function open_JSNISArchive()
	{
		return @fopen($this->options['name'], "rb");
	}
}

class JSNGZIPFile extends JSNISTarFile
{
	function JSNGZIPFile($name)
	{
		$this->JSNISTarFile($name);
		$this->options['type'] = "gzip";
	}

	function create_gzip()
	{
		if ($this->options['inmemory'] == 0)
		{
			$pwd = getcwd();
			chdir($this->options['basedir']);
			if ($fp = gzopen($this->options['name'], "wb{$this->options['level']}"))
			{
				fseek($this->JSNISArchive, 0);
				while ($temp = fread($this->JSNISArchive, 1048576))
					gzwrite($fp, $temp);
				gzclose($fp);
				chdir($pwd);
			}
			else
			{
				$this->error[] = "Could not open {$this->options['name']} for writing.";
				chdir($pwd);
				return 0;
			}
		}
		else
			$this->JSNISArchive = gzencode($this->JSNISArchive);

		return 1;
	}

	function open_JSNISArchive()
	{
		return @gzopen($this->options['name'], "rb");
	}
}

class JSNISBZIPFile extends JSNISTarFile
{
	function JSNISBZIPFile($name)
	{
		$this->JSNISTarFile($name);
		$this->options['type'] = "bzip";
	}

	function createBzip()
	{
		if ($this->options['inmemory'] == 0)
		{
			$pwd = getcwd();
			chdir($this->options['basedir']);
			if ($fp = bzopen($this->options['name'], "wb"))
			{
				fseek($this->JSNISArchive, 0);
				while ($temp = fread($this->JSNISArchive, 1048576))
					bzwrite($fp, $temp);
				bzclose($fp);
				chdir($pwd);
			}
			else
			{
				$this->error[] = "Could not open {$this->options['name']} for writing.";
				chdir($pwd);
				return 0;
			}
		}
		else
			$this->JSNISArchive = bzcompress($this->JSNISArchive, $this->options['level']);

		return 1;
	}

	function open_JSNISArchive()
	{
		return @bzopen($this->options['name'], "rb");
	}
}

class JSNISZIPFile extends JSNISArchive
{
	function JSNISZIPFile($name)
	{
		$this->JSNISArchive($name);
		$this->options['type'] = "zip";
	}

	function createZip()
	{
		$files = 0;
		$offset = 0;
		$central = "";
		
		if (!empty ($this->options['sfx']))
			if ($fp = @fopen($this->options['sfx'], "rb"))
			{
				$temp = fread($fp, filesize($this->options['sfx']));
				fclose($fp);
				$this->addData($temp);
				$offset += strlen($temp);
				unset ($temp);
			}
			else
				$this->error[] = "Could not open sfx module from {$this->options['sfx']}.";

		$pwd = getcwd();
		chdir($this->options['basedir']);
		
		foreach ($this->files as $current)
		{
			if ($current['name'] == $this->options['name'])
				continue;

			$timedate = explode(" ", date("Y n j G i s", $current['stat'][9]));
			$timedate = ($timedate[0] - 1980 << 25) | ($timedate[1] << 21) | ($timedate[2] << 16) |
				($timedate[3] << 11) | ($timedate[4] << 5) | ($timedate[5]);

			$block = pack("VvvvV", 0x04034b50, 0x000A, 0x0000, (isset($current['method']) || $this->options['method'] == 0) ? 0x0000 : 0x0008, $timedate);

			if ($current['stat'][7] == 0 && $current['type'] == 5)
			{
				$block .= pack("VVVvv", 0x00000000, 0x00000000, 0x00000000, strlen($current['name2']) + 1, 0x0000);
				$block .= $current['name2'] . "/";
				$this->addData($block);
				$central .= pack("VvvvvVVVVvvvvvVV", 0x02014b50, 0x0014, $this->options['method'] == 0 ? 0x0000 : 0x000A, 0x0000,
					(isset($current['method']) || $this->options['method'] == 0) ? 0x0000 : 0x0008, $timedate,
					0x00000000, 0x00000000, 0x00000000, strlen($current['name2']) + 1, 0x0000, 0x0000, 0x0000, 0x0000, $current['type'] == 5 ? 0x00000010 : 0x00000000, $offset);
				$central .= $current['name2'] . "/";
				$files++;
				$offset += (31 + strlen($current['name2']));
			}
			else if ($current['stat'][7] == 0)
			{
				$block .= pack("VVVvv", 0x00000000, 0x00000000, 0x00000000, strlen($current['name2']), 0x0000);
				$block .= $current['name2'];
				$this->addData($block);
				$central .= pack("VvvvvVVVVvvvvvVV", 0x02014b50, 0x0014, $this->options['method'] == 0 ? 0x0000 : 0x000A, 0x0000,
					(isset($current['method']) || $this->options['method'] == 0) ? 0x0000 : 0x0008, $timedate,
					0x00000000, 0x00000000, 0x00000000, strlen($current['name2']), 0x0000, 0x0000, 0x0000, 0x0000, $current['type'] == 5 ? 0x00000010 : 0x00000000, $offset);
				$central .= $current['name2'];
				$files++;
				$offset += (30 + strlen($current['name2']));
			}
			else if ($fp = @fopen($current['name'], "rb"))
			{
				$temp = fread($fp, $current['stat'][7]);
				fclose($fp);
				$crc32 = crc32($temp);
				if (!isset($current['method']) && $this->options['method'] == 1)
				{
					$temp = gzcompress($temp, $this->options['level']);
					$size = strlen($temp) - 6;
					$temp = substr($temp, 2, $size);
				}
				else
					$size = strlen($temp);
				$block .= pack("VVVvv", $crc32, $size, $current['stat'][7], strlen($current['name2']), 0x0000);
				$block .= $current['name2'];
				$this->addData($block);
				$this->addData($temp);
				unset ($temp);
				$central .= pack("VvvvvVVVVvvvvvVV", 0x02014b50, 0x0014, $this->options['method'] == 0 ? 0x0000 : 0x000A, 0x0000,
					(isset($current['method']) || $this->options['method'] == 0) ? 0x0000 : 0x0008, $timedate,
					$crc32, $size, $current['stat'][7], strlen($current['name2']), 0x0000, 0x0000, 0x0000, 0x0000, 0x00000000, $offset);
				$central .= $current['name2'];
				$files++;
				$offset += (30 + strlen($current['name2']) + $size);
			}
			else
				$this->error[] = "Could not open file {$current['name']} for reading. It was not added.";
		}

		$this->addData($central);

		$this->addData(pack("VvvvvVVv", 0x06054b50, 0x0000, 0x0000, $files, $files, strlen($central), $offset,
			!empty ($this->options['comment']) ? strlen($this->options['comment']) : 0x0000));

		if (!empty ($this->options['comment']))
			$this->addData($this->options['comment']);

		chdir($pwd);
		
		return 1;
	}
} ?>