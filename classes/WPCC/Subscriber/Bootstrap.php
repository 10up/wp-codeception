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

use Codeception\Event\SuiteEvent;
use Codeception\Events;

/**
 * Event listener responsible for bootstrap action call before suite starts running.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Subscriber
 */
class Bootstrap implements \Symfony\Component\EventDispatcher\EventSubscriberInterface {

	use \Codeception\Subscriber\Shared\StaticEvents;

	/**
	 * Event subscriptions.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $events = array(
		Events::SUITE_BEFORE => 'doBootstrap',
	);

	/**
	 * Calls bootstrap action for a suite.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \Codeception\Event\SuiteEvent $e The event object.
	 */
	public function doBootstrap( SuiteEvent $e ) {
		$settings = $e->getSettings();
		if ( ! isset( $settings['bootstrap'] ) ) {
			return;
		}

		$suite = $e->getSuite();
		do_action( "wpcc_{$suite->baseName}_bootstrap", $suite, $settings );
	}

}