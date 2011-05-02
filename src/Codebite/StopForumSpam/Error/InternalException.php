<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfsintegration
 * @author      Damian Bushong
 * @copyright   (c) 2010 - 2011 Damian Bushong
 * @license     MIT License
 * @link        https://github.com/damianb/SFSIntegration
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace Codebite\StopForumSpam\Error;
use \Codebite\StopForumSpam\Core;

if(!defined('Codebite\\StopForumSpam\\ROOT_PATH')) exit;

/**
 * StopForumSpam Integration - Internal Exception class,
 * 	     used when something goes asplodie internally.
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/damianb/SFSIntegration
 */
class InternalException extends \Exception { }
