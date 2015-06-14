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
use Codeception\Exception\Configuration as ConfigurationException;

/**
 * Loads bootstrap file before tests start.
 *
 * @since 1.0.1
 * @category WPCC
 * @package Subscriber
 */
class Bootstrap extends \Codeception\Subscriber\Bootstrap {

	/**
	 * Loads bootstrap file on \Codeception\Events::SUITE_BEFORE event.
	 * 
	 * @since 1.0.1
	 * @throws \Codeception\Exception\Configuration if a bootstrap file hasn't been found.
	 *
	 * @access public
	 * @param \Codeception\Event\SuiteEvent $e The event object.
	 */
    public function loadBootstrap( SuiteEvent $e ) {
		$settings = $e->getSettings();
		if ( ! isset( $settings['bootstrap'] ) || ! filter_var( $settings['bootstrap'], FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		$bootstrap = $settings['path'] . $settings['bootstrap'];
		if ( ! is_file( $bootstrap ) ) {
			throw new ConfigurationException( "Bootstrap file {$bootstrap} can't be loaded" );
		}

		require_once $bootstrap;
	}

}