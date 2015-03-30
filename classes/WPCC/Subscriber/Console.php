<?php

// +----------------------------------------------------------------------+
// | Copyright 2015 10up Inc                                              |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License, version 2, as  |
// | published by the Free Software Foundation.                           |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               |
// | MA 02110-1301 USA                                                    |
// +----------------------------------------------------------------------+

namespace WPCC\Subscriber;

use \Codeception\Event\TestEvent;
use \Codeception\Event\FailEvent;

/**
 * Listens to global events and displays approprivate messages in the console.
 *
 * @since 1.0.0
 *
 * @category WPCC
 * @package Subscriber
 */
class Console extends \Codeception\Subscriber\Console {

	/**
	 * Clears current line.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _cleanupLine() {
		$this->message( str_pad( '', 80 ) )->write();
	}

	/**
	 * Displays status on success test.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \Codeception\Event\TestEvent $e The event object.
	 */
	public function testSuccess( TestEvent $e ) {
		$this->_cleanupLine();
		parent::testSuccess( $e );
	}

	/**
	 * Displays status on test error.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \Codeception\Event\FailEvent $e The test event.
	 */
	public function testError( FailEvent $e ) {
		$this->_cleanupLine();
        parent::testError( $e );
	}

	/**
	 * Displays skipped test status.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \Codeception\Event\FailEvent $e The test event.
	 */
	public function testSkipped( FailEvent $e ) {
		$this->_cleanupLine();
		parent::testSkipped( $e );
	}

	/**
	 * Displays failed test status.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \Codeception\Event\FailEvent $e The test event.
	 */
	public function testFail( FailEvent $e ) {
		$this->_cleanupLine();
		parent::testFail( $e );
	}

	/**
	 * Displays incomplete test status.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \Codeception\Event\FailEvent $e The test event.
	 */
	public function testIncomplete( FailEvent $e ) {
		$this->_cleanupLine();
		parent::testIncomplete( $e );
	}

}