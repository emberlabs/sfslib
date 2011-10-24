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

namespace emberlabs\sfslib\Transmission\Request;
use \emberlabs\sfslib\Transmission\TransmissionInstanceInterface;
use \emberlabs\sfslib\Transmission\TransmissionErrorInterface;

/**
 * StopForumSpam integration library - Request error object
 * 	     Represents an error response from the StopForumSpam API.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class Error implements TransmissionErrorInterface
{
	protected $errors = array();

	public function __construct(TransmissionInstanceInterface $transmission, array $data)
	{
		if(empty($data))
		{
			$this->errors[] = 'Invalid JSON returned by StopForumSpam API';

			return;
		}

		// asdf
	}

	public function isError()
	{
		return true;
	}

	public function getErrors()
	{
		return $this->errors;
	}
}
