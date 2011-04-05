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
use Codebite\StopForumSpam\Core;

if(!defined('Codebite\\StopForumSpam\\ROOT_PATH')) exit;

/**
 * StopForumSpam Integration - JSON handling class,
 * 		OOP interface for use with JSON files/strings.
 *
 * @package     sfsintegration
 * @author      OpenFlame Development Team, Damian Bushong
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/damianb/SFSIntegration
 *
 * @note        This class should not be instantiated; it should only be statically accessed.
 *
 * @note        This class uses code taken from the OpenFlame Framework, which is licensed under
 *              the MIT License and available at https://github.com/OpenFlame/OpenFlame-Framework
 */
abstract class JSON
{
	/**
	 * Builds a JSON string based on input.
	 * @param mixed $data - The data to cache.
	 * @return string - JSON string.
	 */
	public static function encode($data)
	{
		return json_encode($data);
	}

	/**
	 * Loads a JSON string or file and returns the data held within.
	 * @param string $json - The JSON string or the path of the JSON file to decode.
	 * @return array - The contents of the JSON string/file.
	 *
	 * @throws \RuntimeException
	 */
	public static function decode($json)
	{
		if(is_file($json))
			$json = file_get_contents($json);

		$data = json_decode(preg_replace('#\#.*?' . "\n" . '#', '', $json), true);

		if($data === NULL)
		{
			switch(json_last_error())
			{
				case JSON_ERROR_NONE:
					$error = 'No error';
				break;

				case JSON_ERROR_DEPTH:
					$error = 'Maximum JSON recursion limit reached.';
				break;

				case JSON_ERROR_CTRL_CHAR:
					$error = 'Control character error';
				break;

				case JSON_ERROR_SYNTAX:
					$error = 'JSON syntax error';
				break;

				default:
					$error = 'Unknown JSON error';
				break;
			}

			throw new \RuntimeException(sprintf('JSON error:"%1$s"', $error));
		}

		return $data;
	}
}
