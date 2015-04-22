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
 * Web driver module for BrowserStack provider.
 *
 * @link https://www.browserstack.com/automate/php BrowserStack automated tests
 * @link https://www.browserstack.com/automate/capabilities BrowserStack capabilities
 *
 * @since 1.0.0
 * @category WPCC
 * @package Module
 */
class BrowserStack extends \Codeception\Module\WebDriver {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $config Configuration array.
	 */
	public function __construct( $config = null ) {
		$this->requiredFields[] = 'username';
		$this->requiredFields[] = 'access_key';
		
		parent::__construct( $config );
	}

	/**
	 * Initializes webdriver.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function _initialize() {
		$this->wd_host = sprintf( 'http://%s:%s@hub.browserstack.com/wd/hub', $this->config['username'], $this->config['access_key'] );

		$this->capabilities = $this->config['capabilities'];
		$this->capabilities[ \WebDriverCapabilityType::BROWSER_NAME ] = $this->config['browser'];
		if ( ! empty( $this->config['version'] ) ) {
			$this->capabilities[ \WebDriverCapabilityType::VERSION ] = $this->config['version'];
		}

		$this->webDriver = \RemoteWebDriver::create( $this->wd_host, $this->capabilities );
		$this->webDriver->manage()->timeouts()->implicitlyWait( $this->config['wait'] );

		$this->initialWindowSize();
	}

	/**
	 * Setup initial window size.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function initialWindowSize() {
		if ( isset( $this->config['resolution'] ) ) {
			if ( $this->config['resolution'] == 'maximize' ) {
				$this->maximizeWindow();
			} else {
				$size = explode( 'x', $this->config['resolution'] );
				if ( count( $size ) == 2 ) {
					$this->resizeWindow( intval( $size[0] ), intval( $size[1] ) );
				}
			}
		}
	}

}