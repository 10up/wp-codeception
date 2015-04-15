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

use Codeception\Module\PhpBrowser as CodeceptionPhpBrowser;
use WPCC\Module\Interfaces\WordPress\Web as WordPressWeb;

/**
 * Guzzle based browser module.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Module
 */
class PhpBrowser extends CodeceptionPhpBrowser implements WordPressWeb {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $config Configuration array.
	 */
	public function __construct( $config = null ) {
		// remove "url" field from required fields because it will be automatically populated using home_url() function
		$url_index = array_search( 'url', $this->requiredFields );
		if ( ! empty( $url_index ) ) {
			unset( $this->requiredFields[ $url_index ] );
		}

		// add home url to the config
		$this->config['url'] = home_url();

		// call parent constructor
		parent::__construct( $config );
	}

	/**
	 * Clears browser cookies.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function clearCookies() {
        $this->client->getCookieJar()->clear();
		$this->debugSection( 'Cookies', $this->client->getCookieJar()->all() );
	}

	/**
	 * Goes to a specific admin page. Uses amOnPage method to do a redirect.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $path Optional path relative to the admin url.
	 */
	public function amOnAdminPage( $path = '' ) {
		$this->amOnPage( admin_url( $path ) );
	}
	
	/**
	 * Clicks admin menu item.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $menu The menu item to click on.
	 * @param string $parent The parent menu item to click on first.
	 */
	public function clickAdminMenu( $menu, $parent = null ) {
		$this->click( $menu, '#adminmenu' );
	}

}