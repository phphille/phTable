<?php

//
// Set the error reporting.
//
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);   // Do not buffer outputs, write directly

// Create the session
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();

// Require the database
require "../src/CDatabase.php";

// Database options
$options =  [
    // Set up details on how to connect to the database
    'dsn'     => 'mysql:host=blu-ray.student.bth.se;dbname=phpe14;',
    'username'        => "phpe14",
    'password'        => "RP13Dk&9",
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "",

    // Display details on what happens
    'verbose' => true,

    // Throw a more verbose exception when failing to connect
    //'debug_connect' => 'true',
];

//Get the database class
$db = new \Mos\Database\CDatabase($options);




// SQL search filter to apply in a where statment
function getSqlFilter($title, $description, $releaseYear, $language, $rentalDuration, $rentalRate, $length, $replacementCost, $specialFeatures, $lastUpdate,       $db){


        if ($title) {
            $SQL[] = 'title LIKE "%'.$title.'%"';
        }

        if($description){
            $SQL[] = 'description LIKE "%'.$description.'%"';
        }


        if ($releaseYear) {
            $SQL[] = 'CAST(release_year AS CHAR) LIKE "%'.$releaseYear.'%"';
        }


        if ($language) {
            $SQL[] = 'language LIKE "%'.$language.'%"';
        }


        if ($rentalDuration) {
            $SQL[] = 'CAST(rental_duration AS CHAR)  LIKE "%'.$rentalDuration.'%"';
        }


        if ($rentalRate) {
            $SQL[] = 'CAST(rental_rate AS CHAR) LIKE "%'.$rentalRate.'%"';
        }


        if ($replacementCost) {
            $SQL[] = 'CAST(replacement_cost AS CHAR) LIKE "%'.$replacementCost.'%"';
        }


        if ($specialFeatures) {
            $SQL[] = 'special_features LIKE "%'.$specialFeatures.'%"';
        }


        if ($length) {
            $SQL[] = 'length LIKE "%'.$length.'%"';
        }


        if ($lastUpdate) {
            $SQL[] = 'last_update LIKE "%'.$lastUpdate.'%"';
        }


        if (!empty($SQL)) {
            return ' WHERE '.implode(' AND ',$SQL);
        }

    }



// function to get the data from database
function getData($title, $description, $releaseYear, $language, $rentalDuration, $rentalRate, $length, $replacementCost, $specialFeatures, $lastUpdate, $hits, $page, $orderby, $order, $getAll, $db){

    $params = null;
    $sqlExtra = array();

    $sql = "SELECT DISTINCT
        film_id as Film_id,
        title AS Title,
        release_year AS Release_year,
        language AS Language,
        rental_rate AS Rental_rate,
        rating AS Rating,
           last_update AS Last_update";


    if ($description || $getAll) {
        $sqlExtra[] = "description AS Description";
    }

    if ($rentalDuration || $getAll) {
        $sqlExtra[] = "rental_duration AS Rental_duration";
    }

    if ($length || $getAll) {
        $sqlExtra[] = "length AS Length";
    }

    if ($replacementCost || $getAll) {
        $sqlExtra[] = "replacement_cost AS Replacement_cost";
    }

    if ($specialFeatures || $getAll) {
        $sqlExtra[] = "special_features AS Special_features";
    }


    if($sqlExtra){
        $sql .= ', '.implode($sqlExtra, ',');
    }
        $sql .= "
        FROM film ";

    // Apply the search filter
    $sql .= getSqlFilter($title, $description, $releaseYear, $language, $rentalDuration, $rentalRate, $length, $replacementCost, $specialFeatures, $lastUpdate,        $db);


    $sql .= ' ORDER BY '.$orderby.' '.$order;
    $sqlRows = $sql;

    // Get total rows from the query
    $resRows = $db->ExecuteSelectQueryAndFetchAll($sqlRows, null);

    if($hits != 'All'){
        $sql .= ' LIMIT '.$hits.' OFFSET '.(($page-1) * $hits).";";

    }

    // Get the data to be displayed in the table
    $res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);

    return array('data' => $res, 'nbrRows' => $resRows );

}



// Check if the text fields contains any data and remove any harmful tags.
$title = isset($_POST['Title'])  ? strip_tags($_POST['Title']) : null;
$description = isset($_POST['Description'])  ? strip_tags($_POST['Description']) : null;
$releaseYear = isset($_POST['Release_year'])  ? strip_tags($_POST['Release_year']) : null;
$language = isset($_POST['Language'])  ? strip_tags($_POST['Language']) : null;
$rentalDuration = isset($_POST['Rental_duration']) ? strip_tags($_POST['Rental_duration']) : null;
$rentalRate = isset($_POST['Rental_rate'])  ? strip_tags($_POST['Rental_rate']) : null;
$length = isset($_POST['Length']) ? strip_tags($_POST['Length']) : null;
$replacementCost = isset($_POST['Replacement_cost'])  ? strip_tags($_POST['Replacement_cost']) : null;
$specialFeatures = isset($_POST['Special_features']) ? strip_tags($_POST['Special_features']) : null;
$lastUpdate = isset($_POST['Last_update']) ? strip_tags($_POST['Last_update']) : null;


// Check if the checkboxes are checked.
$noHide = isset($_POST['noHide']) ? strip_tags($_POST['noHide']) : null;
$getAll = isset($_POST['getAll']) ? strip_tags($_POST['getAll']) : null;

// Get the table navigation variables
$hits =  isset($_POST['hits']) && ( is_numeric($_POST['hits']) || $_POST['hits']=='All' ) ? $_POST['hits'] : 20;
$page =  isset($_POST['page']) && is_numeric($_POST['page']) ? $_POST['page'] : 1;
$orderby = isset($_POST['orderby']) ? strtolower(strip_tags($_POST['orderby'])) : 'title';
$order = isset($_POST['order']) ? strtolower(strip_tags($_POST['order']))   : 'asc';

// Check which incoming action is called
$getData = isset($_POST['getData']) ? true : false;
$addData = isset($_POST['addData']) ? true : false;
$deleteData = isset($_POST['deleteData']) ? true : false;
$getCellData = isset($_POST['getCellData']) ? true : false;
$updateCellData = isset($_POST['updateCellData']) ? true : false;

// Variables for update and delete
$id = isset($_POST['id']) && is_numeric($_POST['id']) ? $_POST['id'] : null;
$column = isset($_POST['column']) ? strip_tags($_POST['column']) : null;
$content = isset($_POST['content']) ? strip_tags($_POST['content']) : null;


$data = null;
$rows = null;

// if a search action is called
if($getData){
    $res = getData($title, $description, $releaseYear, $language, $rentalDuration, $rentalRate, $length, $replacementCost, $specialFeatures, $lastUpdate, $hits, $page, $orderby, $order, $getAll, $db);
    $data = $res['data'];
    $rows = count($res['nbrRows']);

    if($noHide){
        $noHide = true;
    }

}

// If the user has dubble clicked on a table cell, then get and send that data back to the plugin
if($getCellData && $id && $column){
    $sql = 'select '.$column.' from film where film_id = ?';
    $res = $db->ExecuteSelectQueryAndFetchAll($sql, array($id));
    if(isset($res[0])){
        $data = $res[0]->$column;
    }
    else{
        $data = null;
    }
}

// if the user has commited a cell change.
if($updateCellData && $id && $column){
    $sql = 'Update film set '.$column.' = ? where film_id = ?';
    $res = $db->ExecuteQuery($sql, array($content, $id));

    if($res){
        $data = $res;
    }

}

// Delete of a table row
if($deleteData && $id){

    $sql = 'DELETE FROM film WHERE film_id = ?';
    $res = $db->ExecuteQuery($sql, array($id));

    if($res){
        $data = $res;
    }
}

// Add new row to the table (database table)
if($addData){
    $columnNames[] = $title != null ? 'title' : null;
    $columnNames[] = $description != null ? 'description' : null;
    $columnNames[] = $releaseYear != null ? 'release_year' : null;
    $columnNames[] = $language != null ? 'language' : null;
    $columnNames[] = $rentalDuration != null ? 'rental_duration' : null;
    $columnNames[] = $rentalRate != null ? 'rental_rate' : null;
    $columnNames[] = $length != null ? 'length' : null;
    $columnNames[] = $replacementCost != null ? 'replacement_cost' : null;
    $columnNames[] = $specialFeatures != null ? 'special_features' : null;

    $columnValues[] = $title != null ? $title : null;
    $columnValues[] = $description != null ? $description : null;
    $columnValues[] = $releaseYear != null ? $releaseYear : null;
    $columnValues[] = $language != null ? $language : null;
    $columnValues[] = $rentalDuration != null ? $rentalDuration : null;
    $columnValues[] = $rentalRate != null ? $rentalRate : null;
    $columnValues[] = $length != null ? $length : null;
    $columnValues[] = $replacementCost != null ? $replacementCost : null;
    $columnValues[] = $specialFeatures != null ? $specialFeatures : null;


    $columns = array();
    $qMark = array();
    $params = array();
    $counter = 0;
    foreach($columnNames as $column){
        if($column){
            $columns[] = $column;
            $qMark[] = '?';
            $params[] = $columnValues[$counter];
        }
        $counter++;
    }
    $sql = 'INSERT INTO film ('.implode($columns,',').') VALUES ('.implode($qMark,',').');';

    $res = $db->ExecuteQuery($sql, $params);

    if($res){
        $data = $db->LastInsertId();
    }
}




// Deliver the response, as a JSON object with the requiered data.
header('Content-type: application/json');
echo json_encode(array("output" => $data, 'noHide' => $noHide, 'nbrRows' => $rows, 'hits' => $hits, 'page' => $page));
