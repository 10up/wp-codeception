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
 * The database module allows you to check whether or not a record exists or
 * fetch rows from the database. The module uses standard instance of wpdb class
 * and not required in configuration.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Module
 */
class Wpdb extends \Codeception\Module implements \Codeception\Lib\Interfaces\Db {

	/**
	 * The database connection.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var \wpdb
	 */
	protected $_wpdb;

	/**
	 * Initializes the module.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @global \wpdb $wpdb The database connection.
	 */
	public function _initialize() {
		global $wpdb;
		$this->_wpdb = $wpdb;
	}

	/**
	 * Builds query.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param string $table The table name.
	 * @param array|string $columns The array of columns to select.
	 * @param array $criteria The array of conditions.
	 * @return string The query string.
	 */
	protected function _prepareQuery( $table, $columns, $criteria ) {
		$where = '1 = 1';
		$params = array();
		
		foreach ( $criteria as $column => $value ) {
			$pattern = '%s';
			if ( is_null( $value ) ) {
				$pattern = '%s AND `%s` IS NULL';
			} elseif ( is_numeric( $value ) ) {
				$pattern = '%s AND `%s` = %%d';
				$params[] = $value;
			} else {
				$pattern = '%s AND `%s` = %%s';
				$params[] = $value;
			}

			$where = sprintf( $pattern, $where, $column );
		}

		if ( is_array( $columns ) ) {
			$columns = implode( ', ', $columns );
		}

		$query = sprintf( 'SELECT %s FROM %s WHERE %s', $columns, $table, $where );
		if ( ! empty( $params ) ) {
			$query = $this->_wpdb->prepare( $query, $params );
		}

		return $query;
	}

	/**
	 * Checks whether or not a record exists in the database.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $table The table name.
	 * @param array $criteria The array of conditions.
	 */
	public function seeInDatabase( $table, $criteria = array() ) {
		$query = $this->_prepareQuery( $table, 'count(*)', $criteria );
		$this->debugSection( 'Query', $query );

		$suppress_errors = $this->_wpdb->suppress_errors( true );
		$res = $this->_wpdb->get_var( $query );
		$this->_wpdb->suppress_errors( $suppress_errors );

		if ( ! empty( $this->_wpdb->last_error ) ) {
			$this->fail( 'Database error: ' . $this->_wpdb->last_error );
			return;
		}

		$this->assertGreaterThan( 0, $res, 'No matching records found' );
	}

	/**
	 * Checks whether or not a record doesn't exist in the database.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $table The table name.
	 * @param array $criteria The array of conditions.
	 */
	public function dontSeeInDatabase( $table, $criteria = array() ) {
		$query = $this->_prepareQuery( $table, 'count(*)', $criteria );
		$this->debugSection( 'Query', $query );

		$suppress_errors = $this->_wpdb->suppress_errors( true );
		$res = $this->_wpdb->get_var( $query );
		$this->_wpdb->suppress_errors( $suppress_errors );

		if ( ! empty( $this->_wpdb->last_error ) ) {
			$this->fail( 'Database error: ' . $this->_wpdb->last_error );
			return;
		}

		$this->assertLessThan( 1, $res, 'Matching records found' );
	}

	/**
	 * Fetches rows from database.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $table The table name.
	 * @param array|string $columns The array of columns to select.
	 * @param array $criteria The array of conditions.
	 * @return array The array of fetched rows.
	 */
	public function grabFromDatabase( $table, $columns, $criteria = array() ) {
		$query = $this->_prepareQuery( $table, $columns, $criteria );
		$this->debugSection( 'Query', $query );

		$suppress_errors = $this->_wpdb->suppress_errors( true );
		$results = $this->_wpdb->get_results( $query );
		$this->_wpdb->suppress_errors( $suppress_errors );

		if ( ! empty( $this->_wpdb->last_error ) ) {
			$this->fail( 'Database error: ' . $this->_wpdb->last_error );
			return;
		}

		return $results;
	}

}