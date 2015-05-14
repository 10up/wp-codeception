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

namespace WPCC\Component\Generator\Sequence;

/**
 * Generates sequence of strings using faker factory.
 *
 * @see https://github.com/fzaninotto/Faker
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Generator
 */
class Faker extends \WPCC\Component\Generator\Sequence {

	/**
	 * The object of faker generator.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @var \Faker\Generator
	 */
	private $_faker;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $template The template string for the sequence.
	 * @param int $start The initial index for the sequence.
	 */
	public function __construct( $template = '%s', $start = 1 ) {
		parent::__construct( $template, $start );
		$this->_faker = \Faker\Factory::create();
	}

	/**
	 * Generates next string and shifts sequence index.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @return string A string based on the sequence template.
	 */
	public function next() {
		$this->_index++;
		return $this->_faker->{$this->_template};
	}

}