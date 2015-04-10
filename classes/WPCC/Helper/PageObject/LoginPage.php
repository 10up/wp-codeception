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

namespace WPCC\Helper\PageObject;

/**
 * Login page helper class.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Helper
 * @subpackage PageObject
 */
class LoginPage extends \WPCC\Helper\PageObject {

	const USERNAME_FIELD = '#user_login';
	const PASSWORD_FIELD = '#user_pass';
	const LOGIN_BUTTON   = '#wp-submit';

	/**
	 * Logins as an user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $username The username to login with.
	 * @param string $password The user password.
	 * @return \WPCC\Helper\PageObject\LoginPage
	 */
	public function login( $username, $password ) {
		$I = $this->_actor;

		$I->amOnPage( wp_login_url() );
		$I->fillField( self::USERNAME_FIELD, $username );
		$I->fillField( self::PASSWORD_FIELD, $password );
		$I->click( self::LOGIN_BUTTON );
		
		return $this;
	}

}