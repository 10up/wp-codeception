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

namespace WPCC\Module\Interfaces\WordPress;

/**
 * Web interface declares common steps for WordPress workflow, which should be
 * implemented by a browser or a webdriver class.
 * 
 * @since 1.0.0
 * @category WPCC
 * @package Module
 * @subpackage Interfaces
 */
interface Web {
	
	/**
	 * Goes to a specific admin page. Uses amOnPage method to do a redirect.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $path Optional path relative to the admin url.
	 */
	public function amOnAdminPage( $path = '' );
	
	/**
	 * Clicks admin menu item.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $menu The menu item to click on.
	 * @param string $parent The parent menu item to click on first.
	 */
	public function clickAdminMenu( $menu, $parent = null );
	
}