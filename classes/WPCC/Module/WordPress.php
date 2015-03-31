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
 * WordPress module.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Module
 */
class WordPress extends \Codeception\Module {

	const USERNAME_FIELD = '#user_login';
	const PASSWORD_FIELD = '#user_pass';
	const LOGIN_BUTTON   = '#wp-submit';

	/**
	 * Goes to the login page.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function amOnLoginPage() {
		if ( $this->hasModule( '\WPCC\Module\WebDriver' ) ) {
			$webdriver = $this->getModule( '\WPCC\Module\WebDriver' );
			$webdriver->amOnPage( wp_login_url() );
		}
	}

	/**
	 * Goes to the login page.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $page Admin dashboard related page.
	 */
	public function amOnAdminPage( $page ) {
		if ( $this->hasModule( '\WPCC\Module\WebDriver' ) ) {
			$webdriver = $this->getModule( '\WPCC\Module\WebDriver' );
			$webdriver->amOnPage( admin_url( $page ) );
		}
	}

	/**
	 * Fills and submits the login form.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $username The username to login with.
	 * @param string $password The user password.
	 * @param string $display_name The user display name, which should be displayed at the admin bar.
	 */
	public function submitLoginForm( $username, $password, $display_name ) {
		if ( $this->hasModule( '\WPCC\Module\WebDriver' ) ) {
			$webdriver = $this->getModule( '\WPCC\Module\WebDriver' );
			
			$webdriver->fillField( self::USERNAME_FIELD, $username );
			$webdriver->fillField( self::PASSWORD_FIELD, $password );
			$webdriver->click( self::LOGIN_BUTTON );
			$webdriver->see( $display_name );
		}
	}

}