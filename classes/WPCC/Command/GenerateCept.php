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

namespace WPCC\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPCC\Component\Generator\Cept;

/**
 * Generates Cept (scenario-driven test) file.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Command
 */
class GenerateCept extends \Codeception\Command\GenerateCept {

	/**
	 * Generates Cept file.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \Symfony\Component\Console\Input\InputInterface $input The input parameters.
	 * @param \Symfony\Component\Console\Output\OutputInterface $output The output interface.
	 */
	public function execute( InputInterface $input, OutputInterface $output ) {
		$suite = $input->getArgument( 'suite' );
		$filename = $input->getArgument( 'test' );

		$config = $this->getSuiteConfig( $suite, $input->getOption( 'config' ) );
		$this->buildPath( $config['path'], $filename );

		$filename = $this->completeSuffix( $filename, 'Cept' );
		$gen = new Cept( $config );

		$path = rtrim( $config['path'], DIRECTORY_SEPARATOR );
		$res = $this->save( $path . DIRECTORY_SEPARATOR . $filename, $gen->produce() );
		if ( ! $res ) {
			$output->writeln( "<error>Test $filename already exists</error>" );
			return;
		}

		$filename = $path . DIRECTORY_SEPARATOR . $filename;
		$output->writeln( "<info>Test was created in $filename</info>" );
	}

}