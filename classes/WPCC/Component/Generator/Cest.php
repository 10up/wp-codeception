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

namespace WPCC\Component\Generator;

use Codeception\Util\Template;

/**
 * Cest files generator.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Generator
 */
class Cest extends \Codeception\Lib\Generator\Cest {

	/**
	 * Produces Cest file and returns it.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @return string The Cest file content.
	 */
	public function produce() {
		$namespace = rtrim( $this->settings['namespace'], '\\' );
		
		$actor = $this->settings['class_name'];
		$ns = "\n" . $this->getNamespaceHeader( $namespace . '\\' . $this->name );
		$ns .= "\nuse " . $namespace . '\\' . $actor . ";";

		$template = new Template( $this->template );
		return $template
				->place( 'name', $this->getShortClassName( $this->name ) )
				->place( 'namespace', $ns )
				->place( 'actor', $actor )
				->produce();
	}

}