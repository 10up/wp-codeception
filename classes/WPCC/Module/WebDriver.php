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

namespace WPCC\Module;

/**
 * Web dirver module.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Module
 */
class WebDriver extends \Codeception\Module\WebDriver {

	/**
	 * Clears browser cookies.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function clearCookies() {
		$this->webDriver->manage()->deleteAllCookies();
		$this->debugSection( 'Cookies', json_encode( $this->webDriver->manage()->getCookies() ) );
	}

}