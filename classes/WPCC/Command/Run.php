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

use WPCC\Codecept;
use Codeception\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Executes tests.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Command
 */
class Run extends \Codeception\Command\Run {

	/**
	 * Executes Run command
	 *
	 * @since 1.0.0
	 * @throws \RuntimeException When a suite is not found.
	 *
	 * @access public
	 * @param \Symfony\Component\Console\Input\InputInterface $input The input arguments.
	 * @param \Symfony\Component\Console\Output\OutputInterface $output The output interface.
	 */
	public function execute( InputInterface $input, OutputInterface $output ) {
		$this->options = $input->getOptions();
		$this->output = $output;

		$config = Configuration::config( $this->options['config'] );

		if ( ! $this->options['colors'] ) {
			$this->options['colors'] = $config['settings']['colors'];
		}
		
		if ( ! $this->options['silent'] ) {
			$this->output->writeln( Codecept::versionString() . "\nPowered by " . \PHPUnit_Runner_Version::getVersionString() );
		}

		if ( $this->options['debug'] ) {
			$this->output->setVerbosity( OutputInterface::VERBOSITY_VERY_VERBOSE );
		}

		$userOptions = array_intersect_key( $this->options, array_flip( $this->passedOptionKeys( $input ) ) );
		$userOptions = array_merge( $userOptions, $this->booleanOptions( $input, ['xml', 'html', 'json', 'tap', 'coverage', 'coverage-xml', 'coverage-html' ] ) );
		$userOptions['verbosity'] = $this->output->getVerbosity();
		$userOptions['interactive'] = ! $input->hasParameterOption( array( '--no-interaction', '-n' ) );

		if ( $this->options['no-colors'] ) {
			$userOptions['colors'] = false;
		}
		if ( $this->options['group'] ) {
			$userOptions['groups'] = $this->options['group'];
		}
		if ( $this->options['skip-group'] ) {
			$userOptions['excludeGroups'] = $this->options['skip-group'];
		}
		if ( $this->options['report'] ) {
			$userOptions['silent'] = true;
		}
		if ( $this->options['coverage-xml'] || $this->options['coverage-html'] || $this->options['coverage-text'] ) {
			$this->options['coverage'] = true;
		}

		$suite = $input->getArgument( 'suite' );
		$test = $input->getArgument( 'test' );

		if ( ! Configuration::isEmpty() && ! $test && strpos( $suite, $config['paths']['tests'] ) === 0 ) {
			list( $matches, $suite, $test ) = $this->matchTestFromFilename( $suite, $config['paths']['tests'] );
		}

		if ( $this->options['group'] ) {
			$this->output->writeln( sprintf( "[Groups] <info>%s</info> ", implode( ', ', $this->options['group'] ) ) );
		}
		if ( $input->getArgument( 'test' ) ) {
			$this->options['steps'] = true;
		}

		if ( $test ) {
			$filter = $this->matchFilteredTestName( $test );
			$userOptions['filter'] = $filter;
		}

		$this->codecept = new Codecept( $userOptions );

		if ( $suite && $test ) {
			$this->codecept->run( $suite, $test );
		}

		if ( ! $test ) {
			$suites = $suite ? explode( ',', $suite ) : Configuration::suites();
			$this->executed = $this->runSuites( $suites, $this->options['skip'] );

			if ( !empty( $config['include'] ) ) {
				$current_dir = Configuration::projectDir();
				$suites += $config['include'];
				$this->runIncludedSuites( $config['include'], $current_dir );
			}

			if ( $this->executed === 0 ) {
				throw new \RuntimeException(
				sprintf( "Suite '%s' could not be found", implode( ', ', $suites ) )
				);
			}
		}

		$this->codecept->printResult();

		if ( ! $input->getOption( 'no-exit' ) ) {
			if ( ! $this->codecept->getResult()->wasSuccessful() ) {
				exit( 1 );
			}
		}
	}

}