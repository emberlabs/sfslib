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
use \emberlabs\sfslib\Library as SFS;
use \OpenFlame\Framework\Core;
use \OpenFlame\Framework\Dependency\Injector;

/**
 * StopForumSpam integration library - Request response object
 * 	     Represents the response from the StopForumSpam API.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class Result
{
	const RESULT_USERNAME = 1;
	const RESULT_EMAIL = 2;
	const RESULT_IP = 3;

	protected $type;

	protected $value;
	protected $normalized;
	protected $lastseen_string;
	protected $lastseen_obj;
	protected $lastseen_diff;
	protected $frequency;
	protected $appears;

	public function __construct($data, $result_type)
	{
		if($result_type != self::RESULT_USERNAME && $result_type != self::RESULT_EMAIL && $result_type != self::RESULT_IP)
		{
			// @todo exception
		}

		$this->type = $result_type;

		$injector = Injector::getInstance();

		$this->value = $data['value'];
		$this->frequency = $data['frequency'];
		if(isset($data['normalized']))
		{
			$this->normalized = $data['normalized'];
		}

		if($data['appears'])
		{
			$this->appears = true;
			$now = $injector->get('sfs.now');

			$this->lastseen_string = $data['lastseen'];
			$this->lastseen_obj = new DateTime('@' . $data['lastseen']);
			$this->lastseen_diff = $this->lastseen_obj->diff($now, true);
		}
		else
		{
			$this->appears = false;
		}
	}

	public function getValue()
	{
		// asdf
	}

	public function getNormalized()
	{
		// asdf
	}

	public function getLastseen()
	{
		// asdf
	}

	public function getLastseenString()
	{
		// asdf
	}

	public function getFrequency()
	{
		// asdf
	}

	public function getAppears()
	{
		// asdf
	}
}
