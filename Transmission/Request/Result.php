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
use \emberlabs\sfslib\Internal\RequestException;
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

	/**
	 * @var integer - The result type.
	 */
	protected $type;

	/**
	 * @var string - The value of this result.
	 */
	protected $value = '';

	/**
	 * @var string - The normalized value of this result (only applies to emails).
	 */
	protected $normalized;

	/**
	 * @var integer - The UNIX timestamp of when the queried data was last seen (if applicable).
	 */
	protected $lastseen;

	/**
	 * @var \DateTime - The DateTime object representing when the queried data was last seen (if applicable).
	 */
	protected $lastseen_obj;

	/**
	 * @var \DateInterval - The DateInterval object representing the interval between now and when the queried data was last seen (if applicable).
	 */
	protected $lastseen_diff;

	/**
	 * @var integer - The number of times that the queried data was reported.
	 */
	protected $frequency;

	/**
	 * @var bool - Whether the queried data has been reported or not.
	 */
	protected $appears;

	/**
	 * Constructor
	 * @throws RequestException
	 */
	public function __construct($data, $result_type)
	{
		// No derpy result types, now.
		if($result_type != self::RESULT_USERNAME && $result_type != self::RESULT_EMAIL && $result_type != self::RESULT_IP)
		{
			throw new RequestException('Invalid result type specified');
		}

		$this->type = $result_type;

		$this->value = $data['value'];
		$this->frequency = $data['frequency'];
		if(isset($data['normalized']))
		{
			$this->normalized = $data['normalized'];
		}

		if($data['appears'])
		{
			$this->appears = true;
			$injector = Injector::getInstance();
			$now = $injector->get('sfs.now');

			$this->lastseen = (int) $data['lastseen'];
			$this->lastseen_obj = new \DateTime('@' . $data['lastseen']);
			$this->lastseen_diff = $this->lastseen_obj->diff($now, true);
		}
		else
		{
			$this->appears = false;
		}
	}

	/**
	 * Get the type of this result
	 * @return integer - Type, as according to the RESULT_* integer constants.
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get the original value queried
	 * @return string - The original value queried.
	 */
	public function getValue()
	{
		return (string) $this->value;
	}

	/**
	 * Get the normalized value if applicable (empty string if no normalization occurred).
	 * @return string - The normalized value.
	 */
	public function getNormalized()
	{
		return (string) $this->normalized;
	}

	/**
	 * Get the DateTime object that represents when the data was last seen.
	 * @return \DateTime|NULL - NULL if not seen previously, or a DateTime object representing when the queried data was last seen.
	 */
	public function getLastseen()
	{
		return $this->lastseen_obj;
	}

	/**
	 * Get the DateInterval object that represents the time span since when the data was last seen.
	 * @return \DateInterval|NULL - NULL if not seen previously, or a DateInterval object representing the time span since when the queried data was last seen.
	 */
	public function getLastseenSpan()
	{
		return $this->lastseen_diff;
	}

	/**
	 * Get the timestamp of when the queried data was last seen
	 * @return integer - The timestamp of when the queried data was last seen (or zero if not seen).
	 */
	public function getLastseenTimestamp()
	{
		return (int) $this->lastseen;
	}

	/**
	 * Get how many times that the queried data has been reported to StopForumSpam.
	 * @return integer - The number of times the queried data has been reported.
	 */
	public function getFrequency()
	{
		return (int) $this->frequency;
	}

	/**
	 * Does the queried data appear in the StopForumSpam database?
	 * @return boolean - Does the data appear?
	 */
	public function getAppears()
	{
		return (bool) $this->appears;
	}
}
