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

namespace WPCC\Component\Factory;

use WPCC\Component\Generator\Sequence;

/**
 * Networks factory.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Factory
 */
class Network extends \WPCC\Component\Factory {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$faker = \Faker\Factory::create();
		$domain = defined( 'WP_TESTS_DOMAIN' ) ? WP_TESTS_DOMAIN : $faker->domainName;

		parent::__construct( array(
			'domain'            => $domain,
			'title'             => new Sequence( 'Network %s' ),
			'path'              => new Sequence( '/testpath%s/' ),
			'network_id'        => new Sequence( '%s', 2 ),
			'subdomain_install' => false,
		) );
	}

	/**
	 * Generates a new network.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param array $args The array of arguments to use during a new object creation.
	 * @return int The newly created network's ID.
	 */
	protected function _createObject( $args ) {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$faker = \Faker\Factory::create();
		$test_email = defined( 'WP_TESTS_EMAIL' ) ? WP_TESTS_EMAIL : $faker->email;

		$email = isset( $args['user'] )
			? get_userdata( $args['user'] )->user_email
			: $test_email;

		$network = populate_network( $args['network_id'], $args['domain'], $email, $args['title'], $args['path'], $args['subdomain_install'] );

		if ( $network && ! is_wp_error( $network ) ) {
			$this->_debug( 'Generated network ID: ' . $args['network_id'] );
		} elseif ( is_wp_error( $network ) ) {
			$this->_debug(
				'Network generation failed with message [%s] %s',
				$network->get_error_code(),
				$network->get_error_messages()
			);
		} else {
			$this->_debug( 'Network generation failed' );
		}

		return $args['network_id'];
	}

	/**
	 * Does nothing, just implements abstract method.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param mixed $object The network id.
	 * @param array $fields The array of fields to update.
	 * @return int The network id.
	 */
	protected function _updateObject( $network_id, $fields ) {
		return $network_id;
	}

	/**
	 * Deletes previously generated network.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @global \wpdb $wpdb The database connection.
	 * @param int $network_id The network id.
	 * @return boolean TRUE on success, otherwise FALSE.
	 */
	protected function _deleteObject( $network_id ) {
		global $wpdb;

		$network_blog = wp_get_sites( array( 'network_id' => $network_id ) );
		if ( ! empty( $network_blog ) ) {
			$suppress = $wpdb->suppress_errors();

			foreach ( $network_blog as $blog ) {
				wpmu_delete_blog( $blog->blog_id, true );
			}

			$wpdb->suppress_errors( $suppress );
		}

		$deleted = $wpdb->delete( $wpdb->site, array( 'id' => $network_id ), array( '%d' ) );
		if ( $deleted ) {
			$wpdb->delete( $wpdb->sitemeta, array( 'site_id' => $network_id ), array( '%d' ) );
			$this->_debug( 'Deleted network with ID: ' . $network_id );

			return true;
		}

		$this->_debug( 'Failed to delet network with ID: ' . $network_id );

		return false;
	}

	/**
	 * Returns generated network by id.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $network_id The network id.
	 * @return object|boolean The generated nework on success, otherwise FALSE.
	 */
	public function getObjectById( $network_id ) {
		return wp_get_network( $network_id );
	}

}