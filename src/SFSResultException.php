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
 * @note reserves 120xx error codes
 */
class SFSResultException extends SFSException
{
	const ERR_NO_ACCESS_DATA_ARRAYACCESS = 12000;
	const ERR_NO_MODIFY_DATA_ARRAYACCESS = 12001;
}
