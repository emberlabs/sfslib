<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfsintegration
 * @author      Damian Bushong
 * @copyright   (c) 2010 - 2011 Damian Bushong
 * @license     MIT License
 * @link        https://github.com/damianb/SFSIntegration
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace Codebite\StopForumSpam;

if(!defined('Codebite\\StopForumSpam\\ROOT_PATH')) exit;

/**
 * StopForumSpam Integration - Autoloader object
 * 	     Provides just-in-time class autoloading functionality.
 *
 * @package     sfsintegration
 * @author      OpenFlame Development Team, Damian Bushong
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/damianb/SFSIntegration
 *
 * @note        This class uses code taken from the OpenFlame Framework, which is licensed under
 *              the MIT License and available at https://github.com/OpenFlame/OpenFlame-Framework
 */
class Autoloader
{
	/**
	 * @var array - The paths that we will attempt to load class files from.
	 */
	private $paths = array();

	/**
	 * Constructor
	 * @param array $paths - Extra paths to include in the autoload search
	 * @return void
	 */
	public function __construct(array $paths = array())
	{
		$paths = array_merge($paths, array(
			\Codebite\StopForumSpam\ROOT_PATH,
		));

		foreach($paths as $path)
		{
			$this->setPath($path);
		}
	}

	/**
	 * Autoload callback for loading class files.
	 * @param string $class - Class to load
	 * @return void
	 *
	 * @throws \RuntimeException
	 */
	public function loadFile($class)
	{
		$name = $this->cleanName($class);

		$filepath = $this->getFile("$name.php");
		if($filepath !== false)
		{
			require $filepath;
			if(!class_exists($class) && !interface_exists($class))
			{
				throw new \RuntimeException(sprintf('Invalid class contained within file %s', $filepath));
			}
			return;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get the correct load path for a specified file
	 * @param string $file - The name of the file to look for
	 * @return mixed - String with the full filepath and name to use for loading, or false if no such file on all registered paths
	 */
	public function getFile($file)
	{
		foreach($this->paths as $path)
		{
			if(file_exists($path . $file))
			{
				return $path . $file;
			}
		}
		return false;
	}

	/**
	 * A quick method to allow adding more include paths to the autoloader.
	 * @param string $include_path - The include path to add to the autoloader
	 * @return void
	 */
	public function setPath($include_path)
	{
		// We use array_unshift here so that newer autoloading paths take priority.
		array_unshift($this->paths, rtrim($include_path, '/') . '/');
	}

	/**
	 * Checks to see whether or not the class file we're looking for exists (and also checks every loading dir)
	 * @param string $class - The class file we're looking for.
	 * @return boolean - Whether or not the source file we're looking for exists
	 */
	public function fileExists($class)
	{
		$name = $this->cleanName($class);

		foreach($this->paths as $path)
		{
			if(file_exists("{$path}{$name}.php"))
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Drop the base namespace if it is there, and replace any backslashes with slashes.
	 * @param string $class_name - The name of the class to spit-polish.
	 * @return string - The cleaned class name.
	 */
	public function cleanName($class)
	{
		return str_replace('\\', '/', ltrim($class, '\\'));
	}

	/**
	 * Register this class as an autoloader within the autoloader stack.
	 * @return \Codebite\StopForumSpam\Autoloader - The newly created autoloader instance.
	 */
	public static function register()
	{
		$self = new self();
		spl_autoload_register(array($self, 'loadFile'));
		return $self;
	}
}
