<?php

// This is the database connection configuration.
return array(
//	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database

	'connectionString' => 'mysql:host=localhost;dbname=cmt-app',
	'emulatePrepare' => true,
	'username' => 'projects',
	'password' => '!Projects123',
	'charset' => 'utf8',

);