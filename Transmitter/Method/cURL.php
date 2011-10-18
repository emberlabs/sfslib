<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfslib
 * @author      emberlabs.org
 * @copyright   (c) 2010 - 2011 Damian Bushong
 * @license     MIT License
 * @link        https://github.com/emberlabs/sfslib
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace emberlabs\sfslib\Transmitter;
use \emberlabs\sfslib\Core;
use \OpenFlame\Framework\Utility\JSON;

if(!defined('Codebite\\StopForumSpam\\ROOT_PATH')) exit;

/**
 * StopForumSpam integration library - Transmitter object
 * 	     Provides functionality to communicate with StopForumSpam.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class cURL implements TransmitterInterface
{
	public function send(\emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission)
	{
		// asdf

		return $transmission->newResponse($json);
	}
}
