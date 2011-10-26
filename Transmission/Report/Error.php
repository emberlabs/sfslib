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
use \emberlabs\sfslib\Transmission\TransmissionInstanceInterface;
use \emberlabs\sfslib\Transmission\TransmissionErrorInterface;

/**
 * StopForumSpam integration library - Report error object
 * 	     Represents an error response from the StopForumSpam API.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class Error implements TransmissionErrorInterface
{
	/**
	 * @var array - Array of errors encountered.
	 */
	protected $errors = array();

	const NO_ERROR = 0;
	const ADD_INVALID_EMAIL = 1;
	const ADD_INVALID_IP = 2;
	const ADD_INVALID_USER = 4;
	const ADD_INVALID_APIKEY = 8;
	const ADD_INVALID_SELF_EMAIL = 16;
	const ADD_INVALID_SELF_IP = 32;
	const ADD_MYSQL_ERROR = 64;
	const ADD_MYSQL_ERROR_SC = 128;
	const ADD_NOSQL_INSERT_ERROR = 256;
	const ADD_DUPLICATE = 512;
	const ADD_SANITY = 1024;

	const ADD_FIELD_ERROR = 32768;
	const MAINT_MODE = 65536;

	protected $error_descs = array(
		0		=> 'No error',
		1		=> 'Invalid email address provided',
		2		=> 'Invalid IP address provided',
		4		=> 'Invalid username provided',
		8		=> 'Invalid API key provided for reporting',
		16		=> 'Cannot report own email',
		32		=> 'Cannot report own IP',
		64		=> 'Database error',
		128		=> '', // ?
		256		=> '', // ?
		512		=> 'Duplicate submission encountered',
		1024	=> '', // ?

		32768	=> 'Serialized data invalid',
		65536	=> 'Maintenance mode',
	);

	/**
	 * Constructor
	 * @param TransmissionInstanceInterface $transmission - The transmission instance linked to this error.
	 * @param array $data - The data array provided by the StopForumSpam API.
	 */
	public function __construct(TransmissionInstanceInterface $transmission, array $data)
	{
		if(empty($data))
		{
			$this->errors[] = 'Invalid JSON returned by StopForumSpam API';

			return;
		}

		if(!$data['success'] && isset($data['errno']))
		{
			$this->errors[] = $data['error'];
			foreach($errors as $error)
			{
				// asdf
			}
		}
		else
		{
			$this->errors[] = 'Unknown error occurred';
		}
	}

	/**
	 * Is this an error object?
	 * @return boolean - It's an error object!  Returns true.
	 */
	public function isError()
	{
		return true;
	}

	/**
	 * Get the errors encountered.
	 * @return array - The array of errors encountered.
	 */
	public function getErrors()
	{
		return $this->errors;
	}
}
