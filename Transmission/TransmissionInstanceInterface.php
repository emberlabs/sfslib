<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfslib
 * @author      emberlabs.org
 * @copyright   (c) 2010 - 2011 emberlabs.org
 * @license     MIT License
 * @link        https://github.com/emberlabs/sfslib
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace emberlabs\sfslib\Transmission;
/**
 * StopForumSpam integration library - Transmission instance interface
 * 	     Provides a prototype of methods that all transmission instances should provide.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
interface TransmissionInstanceInterface
{
	static function newInstance();
	function buildURL();
	function setUsername($username);
	function setEmail($email);
	function setIP($ip);
	function newResponse($json);
	function send();
}
