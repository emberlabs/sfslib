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

namespace emberlabs\sfslib\Transmission\Report;
use \emberlabs\sfslib\Internal\ReportException;
use \emberlabs\sfslib\Transmission\TransmissionInstanceInterface;
use \emberlabs\sfslib\Transmission\Report\Response as ReportResponse;
use \emberlabs\sfslib\Transmission\Report\Error as ReportError;
use \OpenFlame\Framework\Core;
use \OpenFlame\Framework\Dependency\Injector;
use \InvalidArgumentException;

/**
 * StopForumSpam integration library - Report Instance object
 * 	     Represents the report to be made to the StopForumSpam API.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class Instance implements TransmissionInstanceInterface
{
	const API_URL = 'http://www.stopforumspam.com/api';
}
