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

// Define the phar include path
define('SFSLIB_PHAR', 'phar://sfslib.phar/src');

// Grab the main SFS file.
require SFSLIB_PHAR . 'SFS.php';

// Register the autoloader.
spl_autoload_register('SFS::loader');

__HALT_COMPILER(); ?>