<?php     

/*
 * examples/mysql/loaddata.php
 * 
 * This file is part of EditableGrid.
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */
                              


/**
 * This script loads data from the database and returns it to the js
 *
 */
       
require_once('config.php');      
require_once('EditableGrid.php');            

/**
 * fetch_pairs is a simple method that transforms a mysqli_result object in an array.
 * It will be used to generate possible values for some columns.
*/
function fetch_pairs($mysqli,$query){
	if (!($res = $mysqli->query($query)))return FALSE;
	$rows = array();
	while ($row = $res->fetch_assoc()) {
		$first = true;
		$key = $value = null;
		foreach ($row as $val) {
			if ($first) { $key = $val; $first = false; }
			else { $value = $val; break; } 
		}
		$rows[$key] = $value;
	}
	return $rows;
}


// Database connection
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']); 
                    
// create a new EditableGrid object
$grid = new EditableGrid();


	$result = $mysqli->query('SELECT MAX(Expense_ID) FROM Travel_Entries');

	while ($row = mysqli_fetch_array($result)){
		$lastID = $row[0];	

	}


/* 
*  Add columns. The first argument of addColumn is the name of the field in the databse. 
*  The second argument is the label that will be displayed in the header
*/
$grid->addColumn('id', 'ID', 'integer', NULL, false); 
$grid->addColumn('Date', 'Date', 'dtae');  
$grid->addColumn('Time', 'Time', 'string');  
$grid->addColumn('Subject', 'Activity Name', 'string');  
$grid->addColumn('Origin', 'Origin', 'string');  
/* The column id_country and id_continent will show a list of all available countries and continents. So, we select all rows from the tables */
//$grid->addColumn('id_continent', 'Continent', 'string' , fetch_pairs($mysqli,'SELECT id, name FROM continent'),true);  
//$grid->addColumn('id_country', 'Country', 'string', fetch_pairs($mysqli,'SELECT id, name FROM country'),true );  
$grid->addColumn('Destination', 'Destination', 'string');                                               
$grid->addColumn('Mileage', 'Mileage', 'integer');  
$grid->addColumn('Return_Trip', 'Return Trip', 'boolean');  
$grid->addColumn('Remove_Expense', 'Remove Expense', 'boolean');  
                                                                       
$result = $mysqli->query("SELECT * FROM Travel_Entries WHERE Expense_ID = ".$lastID);
$mysqli->close();

// send data to the browser

$grid->renderXML($result);





