<?php

//
// Set the error reporting.
//
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);   // Do not buffer outputs, write directly

// Create the session
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();

require "../src/CDatabase.php";

$options = require "../config_mysql.php";

$db = new \Mos\Database\CDatabase($options);








function getSqlFilter($ID, $Name, $Continent, $Region, $Surface_area, $Indep_year, $Population, $Life_expectancy, $GNP, $GNP_old, $Local_name, $Government_form, $Capital, $Code_2, $Head_of_state,      $db){


        if ($ID) {
            $SQL[] = 'Cast(ID as CHAR) LIKE "%'.$ID.'%"';
        }

        if($Name){
            $SQL[] = 'Name LIKE "%'.$Name.'%"';
        }


        if ($Continent) {
            $SQL[] = 'CAST(Continent AS CHAR) LIKE "%'.$Continent.'%"';
        }


        if ($Region) {
            $SQL[] = 'Region LIKE "%'.$Region.'%"';
        }


        if ($Surface_area) {
            $SQL[] = 'CAST(Surface_area AS CHAR) LIKE "%'.$Surface_area.'%"';
        }


        if ($Indep_year) {
            $SQL[] = 'CAST(Indep_year AS CHAR) LIKE "%'.$Indep_year.'%"';
        }


        if ($Population) {
            $SQL[] = 'CAST(Population AS CHAR) LIKE "%'.$Population.'%"';
        }


        if ($Life_expectancy) {
            $SQL[] = 'CAST(Life_expectancy AS CHAR) LIKE "%'.$Life_expectancy.'%"';
        }


        if ($Head_of_state) {
            $SQL[] = 'CAST(Head_of_state AS CHAR) LIKE "%'.$Head_of_state.'%"';
        }




        if ($GNP) {
            $SQL[] = 'CAST(GNP AS CHAR) LIKE "%'.$GNP.'%"';
        }


        if ($Capital) {
                $SQL[] = 'CAST(Capital AS CHAR) LIKE "%'.$Capital.'%"';
            }

        if ($GNP_old) {
                $SQL[] = 'CAST(GNP_old AS CHAR) LIKE "%'.$GNP_old.'%"';
            }


        if ($Local_name) {
                $SQL[] = 'Local_name LIKE "%'.$Local_name.'%"';
            }


        if ($Government_form) {
                $SQL[] = 'Government_form LIKE "%'.$Government_form.'%"';
            }


        if ($Code_2) {
            $SQL[] = 'Code_2 LIKE "%'.$Code_2.'%"';
        }




        if (!empty($SQL)) {
            return ' WHERE '.implode(' AND ',$SQL);
        }

    }

function getData($ID, $Name, $Continent, $Region, $Surface_area, $Indep_year, $Population, $Life_expectancy, $GNP, $GNP_old, $Local_name, $Government_form, $Capital, $Code_2, $Head_of_state, $hits, $page, $orderby, $order, $getAll, $db, $getRows=null){

    $params = null;
    $sqlExtra = array();

    $sql = "SELECT DISTINCT
        ID,
        Name,
        Continent,
        Region,
        Local_name,
        GNP";


    if ($Surface_area || $getAll) {
        $sqlExtra[] = "Surface_area";
    }

    if ($Indep_year || $getAll) {
        $sqlExtra[] = "Indep_year";
    }

    if ($Population || $getAll) {
        $sqlExtra[] = "Population";
    }

    if ($Life_expectancy || $getAll) {
        $sqlExtra[] = "Life_expectancy";
    }

    if ($GNP_old || $getAll) {
        $sqlExtra[] = "GNP_old";
    }

    if ($Local_name || $getAll) {
        $sqlExtra[] = "Local_name";
    }

    if ($Government_form || $getAll) {
        $sqlExtra[] = "Government_form";
    }

    if ($Capital || $getAll) {
        $sqlExtra[] = "Capital";
    }

    if ($Head_of_state || $getAll) {
        $sqlExtra[] = "Head_of_state";
    }


    if ($Code_2 || $getAll) {
        $sqlExtra[] = "Code_2";
    }


    if($sqlExtra){
        $sql .= ', '.implode($sqlExtra, ',');
    }
        $sql .= "
        FROM Country ";

    $sql .= getSqlFilter($ID, $Name, $Continent, $Region, $Surface_area, $Indep_year, $Population, $Life_expectancy, $GNP, $GNP_old, $Local_name, $Government_form, $Capital, $Code_2, $Head_of_state,        $db);


    $sql .= ' ORDER BY '.$orderby.' '.$order;
    $sqlRows = $sql;

    $resRows = $db->ExecuteSelectQueryAndFetchAll($sqlRows, null);

    if($hits != 'All'){
        $sql .= ' LIMIT '.$hits.' OFFSET '.(($page-1) * $hits).";";

    }

    $res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);


    if($getRows){
        return count($resRows);
    }
    else{
        return (array)$res;
    }

}

$getData = isset($_POST['getData']) ? true : false;
$addData = isset($_POST['addData']) ? true : false;
$deleteData = isset($_POST['deleteData']) ? true : false;

$ID = isset($_POST['ID']) && !empty($_POST['ID']) ? strip_tags($_POST['ID']) : null;
$Name = isset($_POST['Name']) && !empty($_POST['Name']) ? strip_tags($_POST['Name']) : null;
$Continent = isset($_POST['Continent']) && !empty($_POST['Continent']) ? strip_tags($_POST['Continent']) : null;
$Region = isset($_POST['Region']) && !empty($_POST['Region']) ? strip_tags($_POST['Region']) : null;
$Surface_area = isset($_POST['Surface_area']) && !empty($_POST['Surface_area']) ? strip_tags($_POST['Surface_area']) : null;
$Indep_year = isset($_POST['Indep_year']) && !empty($_POST['Indep_year']) ? strip_tags($_POST['Indep_year']) : null;
$Population = isset($_POST['Population']) && !empty($_POST['Population']) ? strip_tags($_POST['Population']) : null;
$Life_expectancy = isset($_POST['Life_expectancy']) && !empty($_POST['Life_expectancy']) ? strip_tags($_POST['Life_expectancy']) : null;
$GNP = isset($_POST['GNP']) && !empty($_POST['GNP']) ? strip_tags($_POST['GNP']) : null;
$GNP_old = isset($_POST['GNP_old']) && !empty($_POST['GNP_old']) ? strip_tags($_POST['GNP_old']) : null;
$Local_name = isset($_POST['Local_name']) && !empty($_POST['Local_name']) ? strip_tags($_POST['Local_name']) : null;
$Government_form = isset($_POST['Government_form']) && !empty($_POST['Government_form']) ? strip_tags($_POST['Government_form']) : null;
$Capital = isset($_POST['Capital']) && !empty($_POST['Capital']) ? strip_tags($_POST['Capital']) : null;
$Code_2 = isset($_POST['Code_2']) && !empty($_POST['Code_2']) ? strip_tags($_POST['Code_2']) : null;
$Head_of_state = isset($_POST['Head_of_state']) && !empty($_POST['Head_of_state']) ? strip_tags($_POST['Head_of_state']) : null;

$noHide = isset($_POST['noHide']) && !empty($_POST['noHide']) ? strip_tags($_POST['noHide']) : null;
$getAll = isset($_POST['getAll']) && !empty($_POST['getAll']) ? strip_tags($_POST['getAll']) : null;


$hits =  isset($_POST['hits']) && ( is_numeric($_POST['hits']) || $_POST['hits']=='All' ) ? strip_tags($_POST['hits']) : 20;
$page =  isset($_POST['page']) && is_numeric($_POST['page']) ? strip_tags($_POST['page']) : 1;
$orderby = isset($_POST['orderby']) ? strtolower($_POST['orderby']) : 'ID';
$order = isset($_POST['order']) ? strtolower($_POST['order'])   : 'asc';


$getCellData = isset($_POST['getCellData']) ? true : false;
$updateCellData = isset($_POST['updateCellData']) ? true : false;
$id = isset($_POST['id']) && is_numeric($_POST['id']) ? $_POST['id'] : null;
$column = isset($_POST['column']) ? strip_tags($_POST['column']) : null;
$content = isset($_POST['content']) ? strip_tags($_POST['content']) : null;


$data = null;
$rows = null;

if($getData){

    $data = getData($ID, $Name, $Continent, $Region, $Surface_area, $Indep_year, $Population, $Life_expectancy, $GNP, $GNP_old, $Local_name, $Government_form, $Capital, $Code_2, $Head_of_state, $hits, $page, $orderby, $order, $getAll, $db);
    $rows = getData($ID, $Name, $Continent, $Region, $Surface_area, $Indep_year, $Population, $Life_expectancy, $GNP, $GNP_old, $Local_name, $Government_form, $Capital, $Code_2, $Head_of_state, $hits, $page, $orderby, $order, $getAll, $db, true);

    if($noHide){
        $noHide = true;
    }

}

if($getCellData && $id && $column){
    $sql = 'select '.$column.' from Country where ID = ?';
    $res = $db->ExecuteSelectQueryAndFetchAll($sql, array($id));
    if(isset($res[0])){
        $data = $res[0]->$column;
    }
    else{
        $data = null;
    }
}

if($updateCellData && $id && $column && $content){
    $sql = 'Update Country set '.$column.' = ? where ID = ?';
    $res = $db->ExecuteQuery($sql, array($content, $id));

    if($res){
        $data = $res;
    }

}

if($deleteData && $id){

    $sql = 'DELETE FROM Country WHERE ID = ?';
    $res = $db->ExecuteQuery($sql, array($id));

    if($res){
        $data = $res;
    }
}


if($addData){
    $columnNames[] = $ID != null ? 'ID' : null;
    $columnNames[] = $Name != null ? 'name' : null;
    $columnNames[] = $Continent != null ? 'Continent' : null;
    $columnNames[] = $Region != null ? 'Region' : null;
    $columnNames[] = $Surface_area != null ? 'Surface_area' : null;
    $columnNames[] = $Indep_year != null ? 'Indep_year' : null;
    $columnNames[] = $Population != null ? 'Population' : null;
    $columnNames[] = $Life_expectancy != null ? 'Life_expectancy' : null;
    $columnNames[] = $GNP != null ? 'GNP' : null;
    $columnNames[] = $GNP_old != null ? 'GNP_old' : null;
    $columnNames[] = $Local_name != null ? 'Local_name' : null;
    $columnNames[] = $Government_form != null ? 'Government_form' : null;
    $columnNames[] = $Capital != null ? 'Capital' : null;
    $columnNames[] = $Code_2 != null ? 'Code_2' : null;
    $columnNames[] = $Head_of_state != null ? 'Head_of_state' : null;

    $columnValues[] = $ID != null ? $ID : null;
    $columnValues[] = $Name != null ? $Name : null;
    $columnValues[] = $Continent != null ? $Continent : null;
    $columnValues[] = $Region != null ? $Region : null;
    $columnValues[] = $Surface_area != null ? $Surface_area : null;
    $columnValues[] = $Indep_year != null ? $Indep_year : null;
    $columnValues[] = $Population != null ? $Population : null;
    $columnValues[] = $Life_expectancy != null ? $Life_expectancy : null;
    $columnValues[] = $GNP != null ? $GNP : null;
    $columnValues[] = $GNP_old != null ? $GNP_old : null;
    $columnValues[] = $Local_name != null ? $Local_name : null;
    $columnValues[] = $Government_form != null ? $Government_form : null;
    $columnValues[] = $Capital != null ? $Capital : null;
    $columnValues[] = $Code_2 != null ? $Code_2 : null;
    $columnValues[] = $Head_of_state != null ? $Head_of_state : null;


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
    $sql = 'INSERT INTO Country ('.implode($columns,',').') VALUES ('.implode($qMark,',').');';

    $res = $db->ExecuteQuery($sql, $params);

    if($res){
        $data = $db->LastInsertId();
    }
}




// Deliver the response, as a JSON object containing the name of the user.
header('Content-type: application/json');
echo json_encode(array("output" => $data, 'noHide' => $noHide, 'nbrRows' => $rows, 'hits' => $hits, 'page' => $page));


