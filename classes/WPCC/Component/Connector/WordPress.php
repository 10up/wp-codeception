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

namespace WPCC\Component\Connector;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;

/**
 * WordPress connector simulates a browser and is used by WordPress module.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Connector
 */
class WordPress extends Client {

	use \Codeception\Lib\Connector\Shared\PhpSuperGlobalsConverter;

	/**
	 * Makes a request.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param \Symfony\Component\BrowserKit\Request $request An origin request instance.
	 * @return \Symfony\Component\BrowserKit\Response An origin response instance.
	 */
	protected function doRequest( Request $request ) {
		$_COOKIE = $request->getCookies();
		$_SERVER = $request->getServer();
		$_FILES = $this->remapFiles( $request->getFiles() );

		$_REQUEST = $this->remapRequestParameters( $request->getParameters() );
		if ( strtoupper( $request->getMethod() ) == 'GET' ) {
			$_GET = $_REQUEST;
		} else {
			$_POST = $_REQUEST;
		}

		$uri = str_replace( 'http://localhost', '', $request->getUri() );

		$_SERVER['REQUEST_METHOD'] = strtoupper( $request->getMethod() );
		$_SERVER['REQUEST_URI'] = $uri;

		ob_start();
//		include $this->index;
		$content = ob_get_contents();
		ob_end_clean();

		$headers = array();
		$php_headers = headers_list();
		foreach ( $php_headers as $value ) {
			// Get the header name
			$parts = explode( ':', $value );
			if ( count( $parts ) > 1 ) {
				$name = trim( array_shift( $parts ) );
				// Build the header hash map
				$headers[ $name ] = trim( implode( ':', $parts ) );
			}
		}

		$headers['Content-type'] = isset( $headers['Content-type'] )
			? $headers['Content-type']
			: "text/html; charset=UTF-8";

		return new Response( $content, 200, $headers );
	}

}