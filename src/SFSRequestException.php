<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfsintegration
 * @author      Damian Bushong
 * @copyright   (c) 2010 Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

/**
 * SFS Integration - Subordinate exception class,
 *      Extension of the SFS exception class.
 *
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 *
 * @note reserves 110xx error codes
 */
class SFSRequestException extends SFSException
{
	const ERR_NO_REQUEST_DATA = 11000;
	const ERR_API_RETURN_EMPTY = 11001;
	const ERR_API_RETURNED_BAD_JSON = 11002;
	const ERR_API_RETURNED_ERROR = 11003;
}
