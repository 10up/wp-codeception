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
use WPCC\Component\Generator\Cest as CestGenerator;

/**
 * Generates Cest (scenario-driven object-oriented test) file.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Command
 */
class GenerateCest extends \Codeception\Command\GenerateCest {

	/**
	 * Generates Cest class.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \Symfony\Component\Console\Input\InputInterface $input The input parameters.
	 * @param \Symfony\Component\Console\Output\OutputInterface $output The output interface.
	 */
	public function execute( InputInterface $input, OutputInterface $output ) {
		$suite = $input->getArgument( 'suite' );
		$class = $input->getArgument( 'class' );

		$config = $this->getSuiteConfig( $suite, $input->getOption( 'config' ) );
		$className = $this->getClassName( $class );
		$path = $this->buildPath( $config['path'], $class );

		$filename = $this->completeSuffix( $className, 'Cest' );
		$filename = $path . $filename;

		if ( file_exists( $filename ) ) {
			$output->writeln( "<error>Test $filename already exists</error>" );
			return;
		}
		$gen = new CestGenerator( $class, $config );
		$res = $this->save( $filename, $gen->produce() );
		if ( !$res ) {
			$output->writeln( "<error>Test $filename already exists</error>" );
			return;
		}

		$output->writeln( "<info>Test was created in $filename</info>" );
	}

}