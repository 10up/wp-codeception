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

use WPCC\Configuration;
use WPCC\Component\Generator\Actor as ActorGenerator;

/**
 * Creates default config, tests directory and sample suites. Use this command
 * to start building a test suite.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Command
 */
class Build extends \Codeception\Command\Build {

	/**
	 * Builds actors for suites.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param string $configFile Alternative config file name.
	 */
    protected function buildActorsForConfig( $configFile ) {
		$config = $this->getGlobalConfig( $configFile );
		$suites = $this->getSuites( $configFile );

		$path = pathinfo( $configFile );
		$dir = isset( $path['dirname'] ) ? $path['dirname'] : getcwd();

		foreach ( $config['include'] as $subConfig ) {
			$this->output->writeln( "<comment>Included Configuration: $subConfig</comment>" );
			$this->buildActorsForConfig( $dir . DIRECTORY_SEPARATOR . $subConfig );
		}

		if ( !empty( $suites ) ) {
			$this->output->writeln( "<info>Building Actor classes for suites: " . implode( ', ', $suites ) . '</info>' );
		}
		foreach ( $suites as $suite ) {
			$settings = $this->getSuiteConfig( $suite, $configFile );
			$gen = new ActorGenerator( $settings );
			$this->output->writeln( '<info>' . Configuration::config()['namespace'] . '\\' . $gen->getActorName() . "</info> includes modules: " . implode( ', ', $gen->getModules() ) );
			$contents = $gen->produce();

			@mkdir( $settings['path'], 0755, true );
			$file = $settings['path'] . $this->getClassName( $settings['class_name'] ) . '.php';
			$this->save( $file, $contents, true );
			$this->output->writeln( "{$settings['class_name']}.php generated successfully. " . $gen->getNumMethods() . " methods added" );
		}
	}

}