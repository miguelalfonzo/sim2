<?php

if( PRODUCCION )
{
	$default = 'oracle';
}
else
{
	$default = 'oraclep';
}

return array(

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/
	'default' => $default,


	'connections' => array(

		'oracle' => array(
			'driver' => 'pdo-via-oci8',
			'host' => '192.168.1.4',
			'port' => '1521',
			'database' => 'BDBAGO',
			'username' => 'sim',
			'password' => 'sim',
			'charset' => 'utf8',
			'prefix' => '',
        ),
        'oraclep' => array(
			'driver' => 'pdo-via-oci8',
			'host' => '192.168.1.4',
			'port' => '1521',
			'database' => 'BDBAGO',
			'username' => 'simp',
			'password' => 'simp',
			'charset' => 'utf8',
			'prefix' => '',
        ),

	),

);
