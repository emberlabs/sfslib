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

namespace emberlabs\sfslib\Transmitter;

/**
 * StopForumSpam integration library - Transmitter interface
 * 	     Provides an interface used for defining a transmitter for when communicating with StopForumSpam.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
interface TransmitterInterface
{
	/**
	 * Send a POST transmission to StopForumSpam
	 * @param \emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission - The transmission to send
	 * @return \emberlabs\sfslib\Transmission\TransmissionResultInterface - The transmission result object
	 */
	function sendGET(\emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission);

	/**
	 * Send a GET transmission to StopForumSpam
	 * @param \emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission - The transmission to send
	 * @return \emberlabs\sfslib\Transmission\TransmissionResultInterface - The transmission result object
	 */
	function sendPOST(\emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission);
}
