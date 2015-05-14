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

		populate_network( $args['network_id'], $args['domain'], $email, $args['title'], $args['path'], $args['subdomain_install'] );

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