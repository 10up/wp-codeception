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

namespace WPCC\Component\Console\Input;

use \Symfony\Component\Console\Input\InputDefinition;

/**
 * ArgvInput represents an input coming from the CLI arguments.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Console
 */
class ArgvInput extends \Symfony\Component\Console\Input\ArgvInput {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @global array $argv Global array of console arguments passed to script.
	 * @param array $args An array of parameters from the CLI (in the argv format).
	 * @param \Symfony\Component\Console\Input\InputDefinition $definition Input definition instance.
	 */
	public function __construct( array $args = null, InputDefinition $definition = null ) {
		global $argv;
		if ( is_null( $args ) ) {
			$args = array_slice( (array) $argv, 1 );
		}

		parent::__construct( $args, $definition );
	}

}