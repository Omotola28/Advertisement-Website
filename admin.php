<?php
session_start();
/**
* Created by PhpStorm.
* User: Omotola
* Date: 08/11/2017
* Time: 00:20
*/
require_once('Models/ExtractProductData.php');
$view = new stdClass();
$view->pageTitle = 'Admin';

$extractProduct = new ExtractProductData();
$query = $extractProduct->fetchAll();
//displays the ads in the admin panel
$view->displayAd = $extractProduct->executeQuery($query);

//applies action to selected products
if(isset($_POST['apply_action'])){
    $ids = $_POST['action_list'];
    $action = $_POST['chooseAction'];
    if($action === "delete"){
         for($i = 0; $i < count($ids); $i++){
            $extractProduct->adminDeleteAd($ids[$i]);
        }
    }
}


require_once('Views/admin.phtml');