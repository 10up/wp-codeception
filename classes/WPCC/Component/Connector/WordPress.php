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
	 * Returns absolute URI.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param string $uri Current URI string.
	 * @return string Absolute URI string.
	 */
	protected function getAbsoluteUri( $uri ) {
        // already absolute?
		if ( 0 === strpos( $uri, 'http' ) ) {
			return $uri;
		}

		$currentUri = ! $this->history->isEmpty()
			? $this->history->current()->getUri()
			: home_url( '/' );

		// protocol relative URL
		if ( 0 === strpos( $uri, '//' ) ) {
			return parse_url( $currentUri, PHP_URL_SCHEME ) . ':' . $uri;
		}

		// anchor?
		if ( ! $uri || '#' == $uri[0] ) {
			return preg_replace( '/#.*?$/', '', $currentUri ) . $uri;
		}

		if ( '/' !== $uri[0] ) {
			$path = parse_url( $currentUri, PHP_URL_PATH );
			if ( '/' !== substr( $path, -1 ) ) {
				$path = substr( $path, 0, strrpos( $path, '/' ) + 1 );
			}

			$uri = $path . $uri;
		}

		return preg_replace( '#^(.*?//[^/]+)\/.*$#', '$1', $currentUri ) . $uri;
	}

	/**
	 * Makes a request.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param \Symfony\Component\BrowserKit\Request $request An origin request instance.
	 * @return \Symfony\Component\BrowserKit\Response An origin response instance.
	 */
	protected function doRequest( $request ) {
		$uri = $request->getUri();
		parse_str( parse_url( $uri, PHP_URL_QUERY ), $_get );

		$_server = $request->getServer();
		$_server['REQUEST_METHOD'] = strtoupper( $request->getMethod() );
		$_server['REQUEST_URI'] = str_replace( home_url(), '', $uri );

		$method = strtoupper( $request->getMethod() );
		$_request = $request->getParameters();
		$_request = array_merge( $_request, $_get );
		
		$args = array(
			'_COOKIE'  => $request->getCookies(),
			'_SERVER'  => $_server,
			'_FILES'   => $this->remapFiles( $request->getFiles() ),
			'_REQUEST' => $_request,
			'_GET'     => $_get,
			'_POST'    => 'POST' == $method ? $_request : array(),
		);
		
		$content = '';

		$stderr = tmpfile();
		$command = sprintf( '%s%smock-request.php "%s"', WPCC_ABSPATH, DIRECTORY_SEPARATOR, http_build_query( $args ) );
		$descriptorspec = array( array( 'pipe', 'r' ), array( 'pipe', 'w' ), $stderr );
		
		$process = proc_open( $command, $descriptorspec, $pipes, ABSPATH );
		if ( $process ) {
			fclose( $pipes[0] );
			$content = stream_get_contents( $pipes[1] );
			fclose( $pipes[1] );
			proc_close( $process );
		}

		fclose( $stderr );

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