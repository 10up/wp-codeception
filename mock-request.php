#!/usr/bin/env php
<?php

$super_vars = array();
parse_str( $argv[1], $super_vars );
foreach( $super_vars as $var => $values ) {
	foreach ( $values as $key => $value ) {
		${$var}[ $key ] = $value;
	}
}

$dir = rtrim( getcwd(), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
$filename = 'index.php';
if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
	$uri = ltrim( current( explode( '?', $_SERVER['REQUEST_URI'] ) ), '/' );
	if ( is_file( $dir . $uri ) ) {
		$filename = str_replace( '/', DIRECTORY_SEPARATOR, $uri );
	}
}

$_SERVER['SCRIPT_FILENAME'] = $dir . $filename;
$_SERVER['SCRIPT_NAME'] = $dir . $filename;
$_SERVER['PATH_TRANSLATED'] = $dir . $filename;
$_SERVER['PHP_SELF'] = DIRECTORY_SEPARATOR . $filename;
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REMOTE_PORT'] = 80;
$_SERVER['SERVER_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_PORT'] = 80;
$_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

$super_vars = $var = $values = $key = $value = $filename = $uri = $dir = null;
unset( $super_vars, $var, $values, $key, $value, $filename, $uri, $dir );

include $_SERVER['SCRIPT_FILENAME'];