<?php 
if(!function_exists('dg_initiate_db_connection')) {
	function dg_initiate_db_connection() {
        // Sample data for the MySQL connection, do not use in production environments.
        $sample_connection = array(
            'port' => 3000,
            'host' => 'example.com',
            'username' => 'dgergo',
            'password' => 'cats',
            'database' => 'sample_database',
        );

        // We are using the mysqli wrapper for connecting to the database
		$connection = new mysqli($sample_connection['host'], $sample_connection['username'], $sample_connection['password'], $sample_connection['database']);
		
		if($connection) {
			mysqli_set_charset($kapcsolat,"utf8");
		}
		
		return $connection;
	}
}
